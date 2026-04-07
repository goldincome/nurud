<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Country;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\BookingConfirmed;
use App\Mail\PaymentConfirmed;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\FlexiApiService;
use App\Services\SimlessPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendBookingConfirmation;
use App\Jobs\SendPaymentConfirmation;
use Illuminate\Support\Facades\Cache;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;



class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected FlexiApiService $flexiService;
    protected SimlessPayService $simlessPayService;

    public function __construct(
        BookingService $bookingService,
        FlexiApiService $flexiService,
        SimlessPayService $simlessPayService
    ) {
        $this->bookingService = $bookingService;
        $this->flexiService = $flexiService;
        $this->simlessPayService = $simlessPayService;
    }

    public function create()
    {
        $verifyId = session()->get('current_verify_id');
        $verifiedOffer = Cache::get('verified_offer_' . $verifyId);
        //dd($verifiedOffer, $verifiedOffer['verifiedPriceBreakdown']['taxesAndFees']);
        if (!$verifiedOffer) {
            return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
        }

        // Retrieve original search params to know how many travelers to show forms for
        $searchId = session()->get('current_search_id');
        $searchData = Cache::get('flight_search_' . $searchId)['search_data'] ?? [];

        $countries = Country::orderBy('name')->get();

        return view('booking.booking', [
            'flightData' => $verifiedOffer,
            'travelerCount' => $searchData['adults'] ?? 1,
            'routeModel' => $searchData['routeModel'] ?? 0,
            'countries' => $countries,
            'total' => ($verifiedOffer['verifiedPriceBreakdown']['total'] + session()->get('markup_fee')),
            'taxes' => $verifiedOffer['verifiedPriceBreakdown']['taxesAndFees'] + session()->get('markup_fee'),
            'simlessPayService' => $this->simlessPayService,
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'passengers' => 'required|array|min:1|max:9',
            'passengers.*.firstName' => 'required|string|max:255',
            'passengers.*.surname' => 'required|string|max:255',
            'passengers.*.dob' => 'required|date',
            'passengers.*.gender' => 'required|in:1,2,3',
        ]);

        $verifyId = session()->get('current_verify_id');
        $verifiedOffer = Cache::get('verified_offer_' . $verifyId);
        //dd($verifiedOffer, $request->all());
        if (!$verifiedOffer) {
            return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
        }
        $i = 1;
        foreach ($request['passengers'] as $index => $passenger) {
            $travelerPayload['travelers'][] = [
                "travelerId" => (string) ($i),
                "firstName" => $passenger['firstName'],
                "lastName" => $passenger['surname'],
                "dateOfBirth" => "1990-01-01",
                "gender" => (int) $passenger['gender']

            ];
            $i++;
        }
        //dd(  $travelerPayload['travelers'], $request['passengers']);

        $payLoad = [
            "officeId" => $verifiedOffer['officeId'],
            "flightOfferId" => $verifiedOffer['offerId'],
            "amaClientRef" => $verifiedOffer['amaClientRef'],
            "travelers" => $travelerPayload['travelers'],
            "travelerContact" => [
                "email" => $request['email'],
                "phone" => $request['phone'],
                "countryCallingCode" => str_replace(')', '', Str::afterLast($request->countryCallingCode, '+')),
                "firstName" => $request['passengers']['adult_1']['firstName'],
                "lastName" => $request['passengers']['adult_1']['surname']
            ],
            "offerInfo" => $verifiedOffer
        ];

        //$result = $this->flexiService->reserveFlight($payLoad);
        // if (!$result) {
        //    return redirect()->back()->with('error', 'Failed to reserve flight');
        //}
        //dd($result);
        // Generate a unique ID to avoid stuffing the SQL Session
        $bookingId = Str::uuid()->toString();

        // Store the heavydata in Cache (expires in 60 mins)
        Cache::put('booking_offer_' . $bookingId, $payLoad, now()->addMinutes(60));

        // Store only the reference ID in the session
        session()->put('offer_data_id', $bookingId);

        $banks = \App\Models\Bank::all();

        return view('booking.checkout', [
            'flightData' => $verifiedOffer,
            'total' => ($verifiedOffer['verifiedPriceBreakdown']['total'] + session()->get('markup_fee')),
            'taxes' => $verifiedOffer['verifiedPriceBreakdown']['taxesAndFees'] + session()->get('markup_fee'),
            'simlessPayService' => $this->simlessPayService,
            'banks' => $banks,
        ]);
    }

    public function store(Request $request)
    {
        $bookingId = session()->get('offer_data_id');
        $bookingOffer = Cache::get('booking_offer_' . $bookingId);

        if (!$bookingOffer) {
            return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
        }
        //dd($bookingOffer);

        try {
            $result = $this->flexiService->reserveFlight($bookingOffer);
            if (!$result) {
                return redirect()->back()->with('error', 'Failed to reserve flight');
            }
            //dd($result);
            // Create booking and all related data in the database
            $booking = $this->bookingService->createPendingBooking($result);

            // Check for Buy Now, Pay Later (BNPL)
            if ($request->booking_type === 'pay_later') {
                // Record the payment method as pay_later
                $booking->payments()->create([
                    'transaction_ref' => 'BNPL_' . strtoupper(uniqid()),
                    'amount' => $booking->total_price,
                    'currency' => $booking->currency,
                    'status' => \App\Enums\PaymentStatus::PENDING,
                    'payment_method' => \App\Enums\PaymentMethod::PAY_LATER,
                ]);

                // Send the BNPL Email
                try {
                    \Illuminate\Support\Facades\Mail::to($booking->customer_email)
                        ->send(new \App\Mail\BuyNowPayLaterEmail($booking, \App\Models\Bank::all()));
                } catch (\Exception $e) {
                    Log::error('Failed to send BNPL email', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                }

                session()->put('booking_id', $booking->id);
                session()->forget(['markup_fee', 'offer_data_id', 'current_verify_id']);
                
                return redirect()->route('bookings.confirmation')->with(
                    'success',
                    'Booking reserved successfully via BNPL Facility! Please check your email and contact us within 12 hours.'
                );
            }

            // Check for Bank Transfer
            if ($request->booking_type === 'bank_transfer') {
                // Record the payment method as bank_transfer
                $booking->payments()->create([
                    'transaction_ref' => 'TRF_' . strtoupper(uniqid()),
                    'amount' => $booking->total_price,
                    'currency' => $booking->currency,
                    'status' => \App\Enums\PaymentStatus::PENDING,
                    'payment_method' => \App\Enums\PaymentMethod::BANK_TRANSFER,
                ]);

                // Send the Bank Transfer Email
                try {
                    \Illuminate\Support\Facades\Mail::to($booking->customer_email)
                        ->send(new \App\Mail\BankTransferBookingEmail($booking, \App\Models\Bank::all()));
                } catch (\Exception $e) {
                    Log::error('Failed to send Bank Transfer email', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                }

                session()->put('booking_id', $booking->id);
                session()->forget(['markup_fee', 'offer_data_id', 'current_verify_id']);
                
                return redirect()->route('bookings.confirmation')->with(
                    'success',
                    'Booking reserved successfully! Please check your email and complete the bank transfer within 12 hours.'
                );
            }

            // Check for Book On Hold
            if ($request->booking_type === 'on_hold') {
                // Record the payment method as book_on_hold
                $booking->payments()->create([
                    'transaction_ref' => 'HOLD_' . strtoupper(uniqid()),
                    'amount' => $booking->total_price,
                    'currency' => $booking->currency,
                    'status' => \App\Enums\PaymentStatus::PENDING,
                    'payment_method' => \App\Enums\PaymentMethod::BOOK_ON_HOLD,
                ]);

                // Send the Book On Hold Email
                try {
                    \Illuminate\Support\Facades\Mail::to($booking->customer_email)
                        ->send(new \App\Mail\BookOnHoldEmail($booking, \App\Models\Bank::all()));
                } catch (\Exception $e) {
                    Log::error('Failed to send Book On Hold email', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage()
                    ]);
                }

                session()->put('booking_id', $booking->id);
                session()->forget(['markup_fee', 'offer_data_id', 'current_verify_id']);
                
                return redirect()->route('bookings.confirmation')->with(
                    'success',
                    'Booking successfully placed on hold! Please check your email and complete the transfer within 12 hours.'
                );
            }

            // Normal Flow Confirmation Email (if not BNPL)
            try {
                //Mail::to($booking->customer_email)->send(new BookingConfirmed($booking));
            } catch (\Exception $e) {
                Log::error('Failed to send booking confirmation email', [
                    'booking_id' => $booking->id,
                    'reservation_id' => $booking->reservation_id,
                    'error' => $e->getMessage()
                ]);
            }

            session()->put('booking_id', $booking->id);
            session()->forget('markup_fee');
            session()->forget('offer_data_id');
            session()->forget('current_verify_id');
            return redirect()->route('bookings.confirmation')->with(
                'success',
                'Booking created successfully. Please complete payment within 24 hours.'
            );

        } catch (\Exception $e) {
            Log::error('Flight reservation failed', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to reserve flight: ' . $e->getMessage());
        }
    }

    public function confirmation()
    {
        $bookingId = session()->get('booking_id');
        $booking = Booking::find($bookingId);
        //dd($verifiedOffer);
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }


        //dd($booking->travelers);
        return view('booking.confirmation', [
            //'flightData' => $verifiedOffer,
            'booking' => $booking->load(['travelers', 'itineraries', 'travelerPricings']),
            'simlessPayService' => $this->simlessPayService,
            'banks' => \App\Models\Bank::all(),
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

    public function payment(string $id): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        // Check permissions
        if ($booking->user_id && $booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow payment for pending bookings
        if ($booking->status !== BookingStatus::PENDING_PAYMENT) {
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
            'payment_method' => 'required|in:' . implode(',', array_column(PaymentMethod::cases(), 'value')),
        ]);

        if ($request->payment_method === PaymentMethod::STRIPE->value) {
            // Process Stripe payment
            try {
                $result = app(PaymentService::class)->processStripePayment([
                    'amount' => $booking->total_amount,
                    'currency' => $booking->currency,
                    'booking_id' => $booking->id,
                    'reservation_id' => $booking->reservation_id,
                ]);

                if ($result['status'] === 'success') {
                    // Create payment record and confirm booking
                    $booking->payments()->create([
                        'transaction_ref' => $result['transaction_id'],
                        'amount' => $result['amount'],
                        'status' => PaymentStatus::COMPLETED,
                    ]);

                    // Confirm the booking
                    app(BookingService::class)->confirmPayment($booking);

                    // Send payment confirmation email
                    try {
                        Mail::to($booking->guest_email)->send(new PaymentConfirmed($booking));
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment confirmation email', ['reservation_id' => $booking->reservation_id, 'error' => $e->getMessage()]);
                    }

                    return redirect()->route('bookings.show', $booking->id)->with('success', 'Payment processed successfully!');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
            }
        } elseif ($request->payment_method === PaymentMethod::BANK_TRANSFER->value) {
            // Create pending payment record for bank transfer
            $booking->payments()->create([
                'transaction_ref' => 'BANK_' . uniqid(),
                'amount' => $booking->total_amount,
                'status' => PaymentStatus::PENDING,
            ]);

            return redirect()->route('bookings.show', $booking->id)->with('success', 'Bank transfer payment initiated. Please complete the transfer to confirm your booking.');
        }

        return back()->with('error', 'Payment method not supported');
    }

    public function downloadTicket(string $id): \Symfony\Component\HttpFoundation\Response
    {
        $booking = Booking::findOrFail($id);

        $user = \Illuminate\Support\Facades\Auth::user();
        $isAdmin = $user && in_array($user->type, [\App\Enums\CustomerType::ADMIN, \App\Enums\CustomerType::SUPERADMIN]);

        // Check permissions
        if ($booking->user_id) {
            if (!$user || ($booking->user_id !== $user->id && !$isAdmin)) {
                abort(403, 'Unauthorized');
            }
        }

        // Only allow download for active bookings (admins can download any status)
        if (!$isAdmin && in_array($booking->status, [BookingStatus::CANCELLED, BookingStatus::EXPIRED])) {
            abort(403, 'Ticket not available for this booking status');
        }

        $booking->load(['travelers', 'payments']);

        $pdf = Pdf::loadView('booking.ticket', compact('booking'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        $filename = 'ticket_' . $booking->reservation_id . '.pdf';

        return $pdf->download($filename);
    }
}
