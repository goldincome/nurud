<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\FlexiApiService;
use App\Services\PaymentService;
use App\Jobs\SendBookingConfirmation;
use App\Jobs\SendPaymentConfirmation;
use App\Mail\BookingConfirmed;
use App\Mail\PaymentConfirmed;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;



class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected FlexiApiService $flexiService;

    public function __construct(BookingService $bookingService, FlexiApiService $flexiService)
    {
        $this->bookingService = $bookingService;
        $this->flexiService = $flexiService;
    }

    public function create(Request $request): View
    {
       $data = session()->get('verified_flight_offer'); //session()get('verified_flight_offer');
        //dd($data);
        // TODO: Load flight details from  session
        return view('booking', [
            'flightData' => $data,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'flight_offer_id' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'guest_email' => 'required|email',
            'guest_phone' => 'nullable|string',
            'travelers' => 'required|array|min:1|max:9',
            'travelers.*.first_name' => 'required|string|max:255',
            'travelers.*.last_name' => 'required|string|max:255',
            'travelers.*.dob' => 'required|date',
            'travelers.*.gender' => 'required|in:M,F',
            'travelers.*.passport_number' => 'required|string|max:255',
            'travelers.*.passport_expiry' => 'required|date|after:today',
            'travelers.*.type' => 'required|in:ADULT,CHILD,INFANT',
        ]);

        try {
            $bookingData = [
                'user_id' => Auth::id(),
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'flight_offer_id' => $request->flight_offer_id,
                'total_amount' => $request->total_amount,
            ];

            $booking = $this->bookingService->createPendingBooking($bookingData, $request->travelers);

            // Send booking confirmation email
            try {
                Mail::to($booking->guest_email)->send(new BookingConfirmed($booking));
            } catch (\Exception $e) {
                Log::error('Failed to send booking confirmation email', ['pnr' => $booking->pnr_reference, 'error' => $e->getMessage()]);
                // Don't fail the booking if email fails
            }

            return response()->json([
                'success' => true,
                'booking' => $booking->load('travelers'),
                'message' => 'Booking created successfully. Please complete payment within 24 hours.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'offer_id' => 'required|string',
        ]);

        try {
            $result = $this->flexiService->verifyPrice($request->offer_id);

            return response()->json([
                'success' => true,
                'verified' => true,
                'price' => $result['price'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'verified' => false,
                'message' => 'Unable to verify flight availability',
            ]);
        }
    }

    public function manage(Request $request): View
    {
        $pnr = $request->query('pnr');
        $email = $request->query('email');

        if (!$pnr || !$email) {
            return redirect()->route('home')->with('error', 'PNR and email are required');
        }

        $booking = $this->bookingService->getBookingByPnr($pnr, $email);

        if (!$booking) {
            return redirect()->route('home')->with('error', 'Booking not found');
        }

        return view('booking.manage', [
            'booking' => $booking->load(['travelers', 'payments']),
        ]);
    }

    public function show(string $id): View
    {
        $booking = Booking::findOrFail($id);

        // Check permissions
        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('booking.show', [
            'booking' => $booking->load(['travelers', 'payments']),
        ]);
    }

    public function payment(string $id): View
    {
        $booking = Booking::findOrFail($id);

        // Check permissions
        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow payment for pending bookings
        if ($booking->status !== 'pending_payment') {
            return redirect()->route('bookings.show', $booking->id)->with('error', 'Payment not available for this booking status');
        }

        return view('booking.payment', [
            'booking' => $booking->load(['travelers', 'user']),
            'bankDetails' => app(PaymentService::class)->getBankTransferInstructions(),
        ]);
    }

    public function processPayment(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        // Check permissions
        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validate payment method
        $request->validate([
            'payment_method' => 'required|in:stripe,bank_transfer',
        ]);

        if ($request->payment_method === 'stripe') {
            // Process Stripe payment
            try {
                $result = app(PaymentService::class)->processStripePayment([
                    'amount' => $booking->total_amount,
                    'currency' => $booking->currency,
                    'booking_id' => $booking->id,
                    'pnr' => $booking->pnr_reference,
                ]);

                if ($result['status'] === 'success') {
                    // Create payment record and confirm booking
                    $booking->payments()->create([
                        'transaction_ref' => $result['transaction_id'],
                        'amount' => $result['amount'],
                        'status' => 'completed',
                    ]);

                    // Confirm the booking
                    app(BookingService::class)->confirmPayment($booking);

                    // Send payment confirmation email
                    try {
                        Mail::to($booking->guest_email)->send(new PaymentConfirmed($booking));
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment confirmation email', ['pnr' => $booking->pnr_reference, 'error' => $e->getMessage()]);
                    }

                    return redirect()->route('bookings.show', $booking->id)->with('success', 'Payment processed successfully!');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
            }
        } elseif ($request->payment_method === 'bank_transfer') {
            // Create pending payment record for bank transfer
            $booking->payments()->create([
                'transaction_ref' => 'BANK_' . uniqid(),
                'amount' => $booking->total_amount,
                'status' => 'pending',
            ]);

            return redirect()->route('bookings.show', $booking->id)->with('success', 'Bank transfer payment initiated. Please complete the transfer to confirm your booking.');
        }

        return back()->with('error', 'Payment method not supported');
    }

    public function downloadTicket(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $booking = Booking::findOrFail($id);

        // Check permissions
        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow download for confirmed bookings
        if ($booking->status !== 'confirmed') {
            abort(403, 'Ticket not available for this booking status');
        }

        $booking->load(['travelers', 'payments']);

        $pdf = Pdf::loadView('booking.ticket', compact('booking'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $filename = 'ticket_' . $booking->pnr_reference . '.pdf';

        return $pdf->download($filename);
    }
}
