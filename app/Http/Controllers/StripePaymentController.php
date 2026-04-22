<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Services\BookingService;
use App\Services\FlexiApiService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use App\Services\SimlessPayService;

class StripePaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected BookingService $bookingService;
    protected FlexiApiService $flexiService;
    protected SimlessPayService $simlessPayService;

    public function __construct(PaymentService $paymentService, BookingService $bookingService, FlexiApiService $flexiService, SimlessPayService $simlessPayService)
    {
        $this->paymentService = $paymentService;
        $this->bookingService = $bookingService;
        $this->flexiService = $flexiService;
        $this->simlessPayService = $simlessPayService;
    }

    public function checkout(Request $request)
    {
        $bookingId = $request->input('booking_id');

        // If no booking_id, we're likely coming from the pre-booking checkout page
        if (!$bookingId) {
            $sessionBookingId = session()->get('offer_data_id');
            $bookingOffer = Cache::get('booking_offer_' . $sessionBookingId);

            if (!$bookingOffer) {
                return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
            }

            //dd(number_format($this->simlessPayService->convertNairaToPounds($bookingOffer['offerInfo']['verifiedPriceBreakdown']['total'] + session()->get('markup_fee'))));
            try {
                // 1. Reserve the flight (get PNR)
                $result = $this->flexiService->reserveFlight($bookingOffer);
                if (!$result) {
                    return back()->with('error', 'Failed to reserve flight with provider.');
                }

                // 2. Create the pending booking
                $booking = $this->bookingService->createPendingBooking($result, $bookingOffer);
                $bookingId = $booking->id;

                // Clean up session if needed or keep it for context
                session()->forget('markup_fee');
                session()->forget('offer_data_id');
                // We'll use this $booking below
            } catch (\Exception $e) {
                Log::error('Stripe pre-checkout reservation failed', ['error' => $e->getMessage()]);
                return back()->with('error', 'Failed to initiate reservation: ' . $e->getMessage());
            }
        } else {
            $booking = Booking::findOrFail($bookingId);
        }
        session()->put('booking_id', $booking->id);
        if ($booking->status === BookingStatus::CONFIRMED && $booking->payment_status === PaymentStatus::COMPLETED) {
            return redirect()->route('bookings.confirmation')->with('success', 'Booking created successfully. Please complete payment within 24 hours.');
        }

        // 3. Create initial payment record
        $payment = Payment::updateOrCreate(
            ['booking_id' => $booking->id, 'payment_method' => PaymentMethod::STRIPE, 'status' => PaymentStatus::PENDING],
            [
                'transaction_ref' => 'STR-' . strtoupper(Str::random(10)),
                'amount' => $booking->total_price,
                'currency' => config('app.currency') ?? 'GBP',
            ]
        );

        $paymentData = [
            'booking_id' => $booking->id,
            'pnr' => $booking->reference_number,
            'amount' => $booking->priceInPounds->total_price,
            'currency' => $booking->priceInPounds->currency ?? 'GBP',
        ];

        // 4. Create Stripe Session
        $response = $this->paymentService->processStripePayment($paymentData);

        if ($response['status'] === 'success') {
            $payment->update([
                'stripe_session_id' => $response['session_id'],
            ]);

            return redirect()->away($response['checkout_url']);
        }

        return back()->with('error', 'Stripe payment error: ' . ($response['error'] ?? 'Unknown error'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        if (!$sigHeader) {
            return response()->json(['error' => 'Missing signature header'], 400);
        }

        $result = $this->paymentService->handleWebhook($payload, $sigHeader);

        if ($result['status'] === 'success' || $result['status'] === 'ignored') {
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['error' => $result['message'] ?? 'Webhook handling failed'], 400);
    }
}
