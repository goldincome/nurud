<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Services\BookingService;
use App\Services\SkyLinkApiService;
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
    protected SkyLinkApiService $skyLinkService;
    protected SimlessPayService $simlessPayService;

    public function __construct(
        PaymentService $paymentService,
        BookingService $bookingService,
        SkyLinkApiService $skyLinkService,
        SimlessPayService $simlessPayService
    ) {
        $this->paymentService = $paymentService;
        $this->bookingService = $bookingService;
        $this->skyLinkService = $skyLinkService;
        $this->simlessPayService = $simlessPayService;
    }

    public function checkout(Request $request)
    {
        $bookingId = $request->input('booking_id');

        if (!$bookingId) {
            $sessionBookingId = session()->get('offer_data_id');
            $bookingPayload = Cache::get('booking_offer_' . $sessionBookingId);

            if (!$bookingPayload) {
                return redirect()->route('search.results')
                    ->with('error', 'Booking session expired. Please re-select your flight.');
            }

            try {
                // Create local pending booking — NO SkyLink reserve call yet
                $flightSummary = $bookingPayload['flight_summary'] ?? [];
                $searchParams = $bookingPayload['search_params'] ?? [];
                //dd($bookingPayload['fare_summary'],$flightSummary, session()->get('markup_fee'));
                $booking = $this->bookingService->createPendingBookingFromSkyLinkPayload(
                    $bookingPayload,
                    $flightSummary,
                    $searchParams
                );
                $bookingId = $booking->id;

                session()->forget('markup_fee');
                session()->forget('offer_data_id');
            } catch (\Exception $e) {
                Log::error('Stripe pre-checkout failed', ['error' => $e->getMessage()]);
                return back()->with('error', 'Failed to initiate booking: ' . $e->getMessage());
            }
        } else {
            $booking = Booking::findOrFail($bookingId);
        }

        session()->put('booking_id', $booking->id);

        if ($booking->status === BookingStatus::CONFIRMED) {
            return redirect()->route('bookings.confirmation')
                ->with('success', 'Booking already confirmed.');
        }

        // Create payment record
        $payment = Payment::updateOrCreate(
            [
                'booking_id' => $booking->id,
                'payment_method' => PaymentMethod::STRIPE,
                'status' => PaymentStatus::PENDING,
            ],
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
