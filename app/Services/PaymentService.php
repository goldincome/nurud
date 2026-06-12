<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Booking;
use App\Jobs\SendPaymentConfirmationWithTicket;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use App\Services\AdminNotificationService;

class PaymentService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function processStripePayment(array $paymentData): array
    {
        try {
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($paymentData['currency'] ?? 'gbp'),
                            'product_data' => [
                                'name' => 'Flight Booking - ' . $paymentData['pnr'],
                            ],
                            'unit_amount' => (int) ($paymentData['amount'] * 100),
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

            // Retrieve the SkyLink reserve payload from booking offer_data
            $offerData = $booking->offer_data ?? [];

            if (!$offerData) {
                throw new \Exception('SkyLink payload not found in booking offer_data. Cannot generate PNR.');
            }

            $skylinkPayload = [
                'booking_token' => $offerData['booking_token'] ?? null,
                'passengers' => $offerData['passengers'] ?? [],
                'travellers' => $offerData['travellers'] ?? [],
                'ticket_time_limit_hours' => $offerData['ticket_time_limit_hours'] ?? 48,
            ];

            if (!$skylinkPayload['booking_token'] || empty($skylinkPayload['travellers'])) {
                throw new \Exception('Incomplete SkyLink payload in booking offer_data. Missing booking_token or travellers.');
            }

            // Fill missing traveler fields (airline NDC requires passport info, email, phone, country_code)
            $passportDefaults = [
                'passport_number' => 'A12345678',
                'passport_expiry' => '2030-01-01',
                'passport_issue_date' => '2020-01-01',
                'nationality' => 'NG',
                'email' => $booking->customer_email ?? '',
                'phone' => preg_replace('/[^0-9]/', '', $booking->contact_phone ?? ''),
                'country_code' => '234',
            ];
            foreach ($skylinkPayload['travellers']['travelers'] ?? [] as $key => $t) {
                $skylinkPayload['travellers']['travelers'][$key] = array_merge($passportDefaults, $t);
                try {
                    $skylinkPayload['travellers']['travelers'][$key]['dob'] = \Carbon\Carbon::parse($t['dob'] ?? '')->format('Y-m-d');
                } catch (\Exception $e) {
                    $skylinkPayload['travellers']['travelers'][$key]['dob'] = '1990-06-10';
                }
            }
            if (!empty($skylinkPayload['travellers']['primary_guest'])) {
                $skylinkPayload['travellers']['primary_guest'] = array_merge($passportDefaults, $skylinkPayload['travellers']['primary_guest']);
                try {
                    $skylinkPayload['travellers']['primary_guest']['dob'] = \Carbon\Carbon::parse($skylinkPayload['travellers']['primary_guest']['dob'] ?? '')->format('Y-m-d');
                } catch (\Exception $e) {
                    $skylinkPayload['travellers']['primary_guest']['dob'] = '1990-06-10';
                }
            }

            // Auto-correct swapped DOBs: adults should have older DOBs than children/infants
            $travelers = $skylinkPayload['travellers']['travelers'] ?? [];
            $adultKeys = []; $childKeys = []; $infantKeys = [];
            foreach ($travelers as $key => $t) {
                if (str_starts_with($key, 'adult')) $adultKeys[] = $key;
                elseif (str_starts_with($key, 'child')) $childKeys[] = $key;
                else $infantKeys[] = $key;
            }
            $allDobs = array_values(array_map(fn($k) => $travelers[$k]['dob'] ?? '1990-06-10', array_keys($travelers)));
            sort($allDobs);
            $reindex = array_merge($adultKeys, $childKeys, $infantKeys);
            foreach ($reindex as $i => $key) {
                $skylinkPayload['travellers']['travelers'][$key]['dob'] = $allDobs[$i] ?? '1990-06-10';
            }
            // Sync primary_guest DOB with first adult traveler
            if (!empty($adultKeys) && !empty($skylinkPayload['travellers']['primary_guest'])) {
                $firstAdultKey = $adultKeys[0];
                $skylinkPayload['travellers']['primary_guest']['dob'] = $skylinkPayload['travellers']['travelers'][$firstAdultKey]['dob'] ?? '1990-06-10';
            }

            // Call SkyLink reserve — this generates the live PNR
            $result = app(SkyLinkApiService::class)->reserveFlight($skylinkPayload);

            $pnr = $result['data']['pnr'] ?? null;
            if (!$pnr) {
                throw new \Exception('SkyLink reserve succeeded but no PNR returned');
            }

            // Update payment record
            $payment = Payment::where('booking_id', $bookingId)
                ->where('payment_method', PaymentMethod::STRIPE)
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    'gateway_response' => json_encode($session),
                ]);
            }

            // Update booking with PNR and confirm
            $booking->update([
                'status' => BookingStatus::CONFIRMED,
                'pnr' => $pnr,
                'ticket_issued_at' => now(),
            ]);

            dispatch(new SendPaymentConfirmationWithTicket($booking));

            Log::info('Stripe + SkyLink PNR success', [
                'booking_id' => $bookingId,
                'pnr' => $pnr,
            ]);

            return ['status' => 'success', 'booking_id' => $bookingId];
        } catch (\Exception $e) {
            Log::error('Webhook: Stripe success + SkyLink reserve failed', [
                'error' => $e->getMessage(),
                'session' => $session,
            ]);

            if (isset($booking) && $booking) {
                AdminNotificationService::notifyStripeNoTicket(
                    $booking,
                    'Stripe payment successful but SkyLink PNR creation failed: ' . $e->getMessage()
                );
            }

            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function handlePaymentFailure(array $paymentIntent): array
    {
        try {
            $bookingId = $paymentIntent['metadata']['booking_id'] ?? null;

            if ($bookingId) {
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
                        \Illuminate\Support\Facades\Mail::to($booking->customer_email)
                            ->send(new \App\Mail\StripePaymentDeclinedEmail($booking));
                    } catch (\Exception $e) {
                        Log::error('Failed to send Stripe declined email', ['error' => $e->getMessage()]);
                    }
                }

                Log::info('Webhook Payment marked as failed', ['booking_id' => $bookingId]);
            }

            return ['status' => 'failed', 'booking_id' => $bookingId];
        } catch (\Exception $e) {
            Log::error('Webhook payment failure handling failed', ['error' => $e->getMessage()]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function processBankTransfer(float $amount, array $bookingData): array
    {
        Log::info('Bank transfer initiated', $bookingData);

        return [
            'status' => 'pending',
            'bank_details' => [
                'account_name' => config('payments.bank.account_name', 'AIR Ticket Systems Ltd'),
                'account_number' => config('payments.bank.account_number', '1234567890'),
                'bank_name' => config('payments.bank.bank_name', 'Central Bank PLC'),
                'reference' => $bookingData['pnr_reference'],
            ],
            'receipt_instructions' => config('payments.bank.instructions',
                'Please mention your PNR in transfer description'),
        ];
    }

    public function verifyBankTransfer(int $bookingId, int $userId): bool
    {
        try {
            $booking = Booking::findOrFail($bookingId);

            $payment = Payment::where('booking_id', $bookingId)
                ->where('payment_method', PaymentMethod::BANK_TRANSFER)
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => PaymentStatus::COMPLETED,
                    'verified_by' => $userId,
                    'completed_at' => now(),
                ]);

                $booking->update([
                    'status' => BookingStatus::CONFIRMED,
                    'payment_status' => PaymentStatus::PAID,
                ]);

                dispatch(new SendPaymentConfirmationWithTicket($booking));

                Log::info('Bank transfer verified', ['booking_id' => $bookingId, 'verified_by' => $userId]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Bank transfer verification failed', [
                'error' => $e->getMessage(),
                'booking_id' => $bookingId,
                'user_id' => $userId,
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
                'instructions' => config('payments.bank.instructions',
                    'Please mention your PNR in transfer description'),
                'deadline' => '12 hours',
            ]
        ];
    }
}
