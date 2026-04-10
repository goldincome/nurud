<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Booking;
use App\Jobs\SendPaymentConfirmation;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class PaymentService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
        // Ensure Stripe client is available for checkout sessions
    }

    public function processStripePayment(array $paymentData): array
    {
        try {
            // Create checkout session
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($paymentData['currency'] ?? 'gbp'),
                            'product_data' => [
                                'name' => 'Flight Booking - ' . $paymentData['pnr'],
                            ],
                            'unit_amount' => (int) ($paymentData['amount'] * 100), // Convert to cents/smallest unit
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('bookings.confirmation') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('bookings.confirmation'),
                'metadata' => [
                    'booking_id' => $paymentData['booking_id'],
                    'pnr' => $paymentData['pnr'],
                ],
            ]);

            Log::info('Stripe checkout session created', [
                'session_id' => $session->id,
                'booking_id' => $paymentData['booking_id']
            ]);

            return [
                'status' => 'success',
                'checkout_url' => $session->url,
                'session_id' => $session->id,
                'transaction_id' => $session->id,
                'amount' => $paymentData['amount'],
            ];

        } catch (\Exception $e) {
            Log::error('Stripe payment failed', [
                'error' => $e->getMessage(),
                'booking_id' => $paymentData['booking_id'] ?? null
            ]);

            return [
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle Stripe webhook events
     */
    public function handleWebhook(string $payload, string $signature): array
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );

            Log::info('Stripe webhook received', ['type' => $event['type']]);

            switch ($event['type']) {
                case 'checkout.session.completed':
                    return $this->handlePaymentSuccess($event['data']['object']->toArray());

                case 'payment_intent.payment_failed':
                    return $this->handlePaymentFailure($event['data']['object']->toArray());

                default:
                    Log::info('Unhandled webhook event', ['type' => $event['type']]);
                    return ['status' => 'ignored'];
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess(array $session): array
    {
        try {
            $bookingId = $session['metadata']['booking_id'] ?? null;

            if (!$bookingId) {
                throw new \Exception('No booking ID found in session metadata');
            }

            $booking = Booking::find($bookingId);

            if (!$booking) {
                throw new \Exception('Booking not found');
            }

            // Update payment record
            $payment = Payment::where('booking_id', $bookingId)
                ->where('payment_method', PaymentMethod::STRIPE)
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    // 'transaction_id' => $session['payment_intent'] ?? $session['id'],
                    'gateway_response' => json_encode($session),
                ]);
            }

            // Update booking status
            $booking->update([
                'status' => BookingStatus::CONFIRMED,
                //'payment_method' => PaymentMethod::STRIPE,
                //'payment_status' => PaymentStatus::PAID,
            ]);

            // Send payment confirmation email
            dispatch(new SendPaymentConfirmation($booking));

            Log::info('Webhook Payment processed successfully', [
                'booking_id' => $bookingId,
                'session_id' => $session['id'],
                'booking data' => $booking,
                'payment data' => $payment
            ]);

            return ['status' => 'success', 'booking_id' => $bookingId];

        } catch (\Exception $e) {
            Log::error('Webhook Payment success handling failed', [
                'error' => $e->getMessage(),
                'session' => $session
            ]);

            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailure(array $paymentIntent): array
    {
        try {
            $bookingId = $paymentIntent['metadata']['booking_id'] ?? null;

            if ($bookingId) {
                // Update payment record
                $payment = Payment::where('booking_id', $bookingId)
                    ->where('payment_method', PaymentMethod::STRIPE)
                    ->first();

                if ($payment) {
                    $payment->update([
                        'status' => PaymentStatus::FAILED,
                        'gateway_response' => json_encode($paymentIntent),
                    ]);
                }

                $booking = Booking::find($bookingId);
                if ($booking && $booking->customer_email) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($booking->customer_email)->send(new \App\Mail\StripePaymentDeclinedEmail($booking));
                    } catch (\Exception $e) {
                        Log::error('Failed to send Stripe payment declined email', ['error' => $e->getMessage()]);
                    }
                }

                Log::info('Webhook Payment marked as failed', ['booking_id' => $bookingId]);
            }

            return ['status' => 'failed', 'booking_id' => $bookingId];

        } catch (\Exception $e) {
            Log::error('Webhook Payment failure handling failed', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function processBankTransfer(float $amount, array $bookingData): array
    {
        // Bank transfer is manual - create pending payment record
        Log::info('Bank transfer initiated', $bookingData);

        return [
            'status' => 'pending',
            'bank_details' => [
                'account_name' => config('payments.bank.account_name', 'AIR Ticket Systems Ltd'),
                'account_number' => config('payments.bank.account_number', '1234567890'),
                'bank_name' => config('payments.bank.bank_name', 'Central Bank PLC'),
                'reference' => $bookingData['pnr_reference'],
            ],
            'receipt_instructions' => config('payments.bank.instructions', 'Please mention your PNR in transfer description'),
        ];
    }

    public function verifyBankTransfer(int $bookingId, int $userId): bool
    {
        try {
            $booking = Booking::findOrFail($bookingId);

            // Update payment record
            $payment = Payment::where('booking_id', $bookingId)
                ->where('payment_method', PaymentMethod::BANK_TRANSFER)
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    'verified_by' => $userId,
                    'completed_at' => now(),
                ]);

                // Update booking status
                $booking->update([
                    'status' => BookingStatus::CONFIRMED,
                    'payment_status' => PaymentStatus::PAID,
                ]);

                // Send payment confirmation email
                dispatch(new SendPaymentConfirmation($booking));

                Log::info('Bank transfer verified', ['booking_id' => $bookingId, 'verified_by' => $userId]);
                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Bank transfer verification failed', [
                'error' => $e->getMessage(),
                'booking_id' => $bookingId,
                'user_id' => $userId
            ]);

            return false;
        }
    }

    public function getBankTransferInstructions(): array
    {
        return \App\Models\Bank::all()->toArray() ?: [
            [
                'account_name' => config('payments.bank.account_name', 'AIR Ticket Systems Ltd'),
                'account_number' => config('payments.bank.account_number', '1234567890'),
                'bank_name' => config('payments.bank.bank_name', 'Central Bank PLC'),
                'instructions' => config('payments.bank.instructions', 'Please mention your PNR in transfer description'),
                'deadline' => '12 hours',
            ]
        ];
    }
}
