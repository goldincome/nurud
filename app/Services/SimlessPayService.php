<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SimlessPayService
{
    // Payment Channel Type Constants (PaymentChannelTypeEnum)
    public const CHANNEL_OPEN_BANKING = 1;
    public const CHANNEL_CARD_PAYMENT = 2;

    // Transaction Status Code Constants
    public const STATUS_COMPLETED = '00';
    public const STATUS_CREATED = '01';
    public const STATUS_PAYIN_INITIATION_PROCESS_COMPLETED = '02';
    public const STATUS_PAYIN_INITIATION_FAILED_OR_CANCELLED = '03';
    public const STATUS_PAYIN_INITIATION_EXECUTED = '04';
    public const STATUS_PAYIN_UNDER_COMPLIANCE_CHECK = '05';
    public const STATUS_PAYIN_COMPLIANCE_SUCCESS_CREDITED = '06';
    public const STATUS_PAYIN_COMPLIANCE_FAILED_REFUNDED = '07';
    public const STATUS_PAYIN_CREDITED_BUT_ON_HOLD = '08';
    public const STATUS_PAYOUT_IN_PROGRESS = '09';
    public const STATUS_PAYOUT_MIGHT_FAILED = '10';
    public const STATUS_REFUNDING_IN_PROGRESS = '11';
    public const STATUS_REFUNDED = '12';

    protected string $baseUrl;
    protected string $apiKey;
    protected string $tokenCacheKey = 'simlesspay_token';

    public function __construct()
    {
        $this->baseUrl = config('simlesspay.base_url');
        $this->apiKey = config('simlesspay.api_key');
    }

    /**
     * Get the JWT token from cache or authenticate.
     */
    protected function getToken(): ?string
    {
        return Cache::remember($this->tokenCacheKey, 3500, function () {
            return $this->authenticate();
        });
    }

    /**
     * Authenticate with the API using the API Key.
     */
    protected function authenticate(): ?string
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
            ])
                ->acceptJson()
                ->get("{$this->baseUrl}/api/v1/auth/login/api-key");

            if ($response->successful()) {
                return $response->json('result.token');
            }

            $this->logError('Authentication failed', $response);
            return null;
        } catch (\Exception $e) {
            $this->logException('Authentication exception', $e);
            return null;
        }
    }

    /**
     * Get a pre-configured HTTP client.
     */
    protected function getHttpClient()
    {
        $token = $this->getToken();

        return Http::withToken($token)
            ->acceptJson()
            ->timeout(60)
            ->baseUrl($this->baseUrl);
    }

    /**
     * List Payout Channels.
     */
    public function getPayoutChannels(): array
    {
        try {
            $response = $this->getHttpClient()->get('/api/v1/pay-out-channel/list');

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json('result')];
            }

            return $this->handleErrorResponse('Failed to list payout channels', $response);
        } catch (\Exception $e) {
            return $this->handleException('Payout channel list exception', $e);
        }
    }

    /**
     * Create a Recipient.
     */
    public function createRecipient(array $data): array
    {
        try {
            $response = $this->getHttpClient()->post('/api/v1/recipient', $data);

            if ($response->successful() || $response->status() === 201) {
                return ['success' => true, 'data' => $response->json('result')];
            }

            return $this->handleErrorResponse('Failed to create recipient', $response);
        } catch (\Exception $e) {
            return $this->handleException('Recipient creation exception', $e);
        }
    }

    /**
     * List Recipients.
     */
    public function listRecipients(int $take = 10, int $skip = 0, array $params = []): array
    {
        try {
            $payload = [
                'take' => $take,
                'skip' => $skip,
            ];

            if (!empty($params)) {
                $payload['params'] = $params;
            }

            $response = $this->getHttpClient()->post('/api/v1/recipient/list', $payload);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json('result')];
            }

            return $this->handleErrorResponse('Failed to list recipients', $response);
        } catch (\Exception $e) {
            return $this->handleException('Recipient list exception', $e);
        }
    }

    /**
     * Get a specific Recipient.
     */
    public function getRecipient(int $recipientId): array
    {
        try {
            $response = $this->getHttpClient()->get('/api/v1/recipient', [
                'recipient_id' => $recipientId
            ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json('result')];
            }

            return $this->handleErrorResponse('Failed to get recipient', $response);
        } catch (\Exception $e) {
            return $this->handleException('Get recipient exception', $e);
        }
    }

    /**
     * Create a Quotation for a Transaction.
     *
     * Gets exchange rate, fees, and total payable before initiating a transaction.
     *
     * @param int    $payOutChannelId      Payout channel ID (e.g., 2 for GBP→NGN, 4 for EUR→NGN)
     * @param float  $sourceAmount         Amount being sent from the source country
     * @param string $sourceCountryCode    ISO country code of sender (e.g., 'GB', 'NL')
     * @param string $destinationCountryCode ISO country code of receiver (e.g., 'NG')
     * @param string $feePayer             Who pays fees: 'sender' or 'receiver'
     */
    public function createQuotation(
        int $payOutChannelId,
        float $sourceAmount,
        string $sourceCountryCode = 'GB',
        string $destinationCountryCode = 'NG',
        string $feePayer = 'sender'
    ): array {
        try {
            $response = $this->getHttpClient()->post('/api/v1/transaction/quotation', [
                'pay_out_channel_id' => $payOutChannelId,
                'source_amount' => $sourceAmount,
                'source_country_code' => $sourceCountryCode,
                'destination_country_code' => $destinationCountryCode,
                'fee_payer' => $feePayer,
            ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return $this->handleErrorResponse('Failed to create quotation', $response);
        } catch (\Exception $e) {
            return $this->handleException('Quotation creation exception', $e);
        }
    }

    /**
     * Initiate a Transaction.
     *
     * @param array $data Transaction data including:
     *   - pay_out_channel_id (int): Payout channel ID
     *   - source_amount (float): Amount to send
     *   - recipient_id (int): Recipient ID
     *   - source_country_code (string): Sender's country ISO code
     *   - destination_country_code (string): Receiver's country ISO code
     *   - narration (string): Transaction description
     *   - external_reference (string): Unique external reference
     *   - payment_channel_type (int): 1 = OPEN_BANKING, 2 = CARD_PAYMENT
     *   - fee_payer (string): 'sender' or 'receiver'
     *   - options (array): Optional. For CARD_PAYMENT: ['client_email' => 'email@example.com']
     */
    public function initiateTransaction(array $data): array
    {
        try {
            $response = $this->getHttpClient()->post('/api/v1/transaction', $data);

            if ($response->successful() || $response->status() === 201) {
                return ['success' => true, 'data' => $response->json()];
            }

            return $this->handleErrorResponse('Failed to initiate transaction', $response);
        } catch (\Exception $e) {
            return $this->handleException('Transaction initiation exception', $e);
        }
    }

    /**
     * Initiate a Card Payment Transaction.
     *
     * Convenience method that sets payment_channel_type to CARD_PAYMENT
     * and includes the required client_email in options.
     *
     * @param int    $payOutChannelId       Payout channel ID
     * @param float  $sourceAmount          Amount to send
     * @param int    $recipientId           Recipient ID
     * @param string $clientEmail           Client's email (required for card payments)
     * @param string $narration             Transaction description
     * @param string $externalReference     Unique external reference for tracking
     * @param string $sourceCountryCode     Sender's country code (default: 'GB')
     * @param string $destinationCountryCode Receiver's country code (default: 'NG')
     * @param string $feePayer              Who pays fees: 'sender' or 'receiver'
     */
    public function initiateCardPayment(
        int $payOutChannelId,
        float $sourceAmount,
        int $recipientId,
        string $clientEmail,
        string $narration = '',
        string $externalReference = '',
        string $sourceCountryCode = 'GB',
        string $destinationCountryCode = 'NG',
        string $feePayer = 'sender'
    ): array {
        return $this->initiateTransaction([
            'pay_out_channel_id' => $payOutChannelId,
            'source_amount' => $sourceAmount,
            'recipient_id' => $recipientId,
            'source_country_code' => $sourceCountryCode,
            'destination_country_code' => $destinationCountryCode,
            'narration' => $narration,
            'external_reference' => $externalReference ?: strtoupper(\Illuminate\Support\Str::random(16)),
            'payment_channel_type' => self::CHANNEL_CARD_PAYMENT,
            'options' => [
                'client_email' => $clientEmail,
            ],
            'fee_payer' => $feePayer,
        ]);
    }

    /**
     * Initiate an Open Banking Transaction.
     *
     * Convenience method that sets payment_channel_type to OPEN_BANKING.
     *
     * @param int    $payOutChannelId       Payout channel ID
     * @param float  $sourceAmount          Amount to send
     * @param int    $recipientId           Recipient ID
     * @param string $narration             Transaction description
     * @param string $externalReference     Unique external reference for tracking
     * @param string $sourceCountryCode     Sender's country code (default: 'GB')
     * @param string $destinationCountryCode Receiver's country code (default: 'NG')
     * @param string $feePayer              Who pays fees: 'sender' or 'receiver'
     */
    public function initiateOpenBankingPayment(
        int $payOutChannelId,
        float $sourceAmount,
        int $recipientId,
        string $narration = '',
        string $externalReference = '',
        string $sourceCountryCode = 'GB',
        string $destinationCountryCode = 'NG',
        string $feePayer = 'sender'
    ): array {
        return $this->initiateTransaction([
            'pay_out_channel_id' => $payOutChannelId,
            'source_amount' => $sourceAmount,
            'recipient_id' => $recipientId,
            'source_country_code' => $sourceCountryCode,
            'destination_country_code' => $destinationCountryCode,
            'narration' => $narration,
            'external_reference' => $externalReference ?: strtoupper(\Illuminate\Support\Str::random(16)),
            'payment_channel_type' => self::CHANNEL_OPEN_BANKING,
            'fee_payer' => $feePayer,
        ]);
    }

    /**
     * Get the exchange rate for a specific payout channel with caching.
     * * @param int $channelId The ID of the channel (e.g., the one for GBP/NGN)
     * @param int $ttl Seconds to cache (default 3600 / 1 hour)
     */
    public function getCachedExchangeRate(int $channelId = 2, int $ttl = 3600): float
    {
        $cacheKey = "simlesspay_rate_channel_{$channelId}";

        return Cache::remember($cacheKey, $ttl, function () use ($channelId) {
            $channels = $this->getPayoutChannels();
            //dd($channels);
            // Find the specific channel by ID to be safe
            $channel = collect($channels['data']['pay_out_channels'] ?? [])
                ->firstWhere('id', $channelId);

            if (!$channel || !isset($channel['exchange_rate'])) {
                throw new \Exception("Exchange rate for channel {$channelId} not found.");
            }

            return (float) $channel['exchange_rate'];
        });
    }

    /**
     * Convert Naira to Pounds using the cached rate.
     */
    public function convertNairaToPounds(float $nairaAmt): float
    {
        // Assuming Channel ID 2 is the Naira/Pounds channel from your JSON
        $rate = $this->getCachedExchangeRate(2);

        $convertedAmt = $nairaAmt / $rate;

        return (float) number_format($convertedAmt, 2, '.', '');
    }

    /**
     * Get a specific Transaction.
     */
    public function getTransaction(int $transactionId): array
    {
        try {
            $response = $this->getHttpClient()->get('/api/v1/transaction', [
                'transaction_id' => $transactionId
            ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json('result')];
            }

            return $this->handleErrorResponse('Failed to get transaction', $response);
        } catch (\Exception $e) {
            return $this->handleException('Get transaction exception', $e);
        }
    }

    /**
     * List Transactions.
     */
    public function listTransactions(int $take = 10, int $skip = 0): array
    {
        try {
            $response = $this->getHttpClient()->post('/api/v1/transaction/list', [
                'take' => $take,
                'skip' => $skip,
            ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json('result')];
            }

            return $this->handleErrorResponse('Failed to list transactions', $response);
        } catch (\Exception $e) {
            return $this->handleException('Transaction list exception', $e);
        }
    }

    /**
     * Get the full transaction status map.
     */
    public static function getStatusMap(): array
    {
        return [
            '00' => 'COMPLETED',
            '01' => 'CREATED',
            '02' => 'PAYIN_INITIATION_PROCESS_COMPLETED',
            '03' => 'PAYIN_INITIATION_FAILED_OR_CANCELLED',
            '04' => 'PAYIN_INITIATION_EXECUTED',
            '05' => 'PAYIN_AMOUNT_CREDITING_UNDER_COMPLIANCE_CHECK',
            '06' => 'PAYIN_COMPLIANCE_SUCCESS_CREDITED',
            '07' => 'PAYIN_COMPLIANCE_FAILED_REFUNDED',
            '08' => 'PAYIN_CREDITED_BUT_ON_HOLD',
            '09' => 'PAYOUT_IN_PROGRESS',
            '10' => 'PAYOUT_MIGHT_FAILED',
            '11' => 'REFUNDING_IN_PROGRESS',
            '12' => 'REFUNDED',
        ];
    }

    /**
     * Resolve a raw status code to a human-readable name.
     */
    public static function resolveStatus(string $statusCode): string
    {
        return self::getStatusMap()[$statusCode] ?? 'UNKNOWN';
    }

    /**
     * Check if a transaction status indicates success / completion.
     */
    public static function isSuccessStatus(string $statusCode): bool
    {
        return $statusCode === self::STATUS_COMPLETED;
    }

    /**
     * Check if a transaction status indicates failure / refund.
     */
    public static function isFailedStatus(string $statusCode): bool
    {
        return in_array($statusCode, [
            self::STATUS_PAYIN_INITIATION_FAILED_OR_CANCELLED,
            self::STATUS_PAYIN_COMPLIANCE_FAILED_REFUNDED,
            self::STATUS_PAYOUT_MIGHT_FAILED,
            self::STATUS_REFUNDED,
        ]);
    }

    /**
     * Check if a transaction status indicates it's still in progress.
     */
    public static function isPendingStatus(string $statusCode): bool
    {
        return in_array($statusCode, [
            self::STATUS_CREATED,
            self::STATUS_PAYIN_INITIATION_PROCESS_COMPLETED,
            self::STATUS_PAYIN_INITIATION_EXECUTED,
            self::STATUS_PAYIN_UNDER_COMPLIANCE_CHECK,
            self::STATUS_PAYIN_COMPLIANCE_SUCCESS_CREDITED,
            self::STATUS_PAYIN_CREDITED_BUT_ON_HOLD,
            self::STATUS_PAYOUT_IN_PROGRESS,
            self::STATUS_REFUNDING_IN_PROGRESS,
        ]);
    }

    /**
     * Handle incoming webhooks (Transaction status updates).
     */
    public function handleWebhook(array $payload): array
    {
        try {
            Log::info('TetradPay Webhook received', ['payload' => $payload]);

            $reference = $payload['reference'] ?? null;
            $status = $payload['status'] ?? null;

            if (!$reference) {
                return [
                    'success' => false,
                    'error' => 'Missing transaction reference',
                ];
            }

            $resolvedStatus = self::resolveStatus($status);

            // Logic to update local database would go here
            // Example: Payment::where('transaction_ref', $reference)->update(['status' => $resolvedStatus]);

            return [
                'success' => true,
                'message' => 'Webhook processed successfully',
                'internal_status' => $resolvedStatus,
                'is_completed' => self::isSuccessStatus($status),
                'is_failed' => self::isFailedStatus($status),
                'is_pending' => self::isPendingStatus($status),
                'raw_status' => $status,
                'payment_channel_type' => $payload['payment_channel_type'] ?? null,
            ];
        } catch (\Exception $e) {
            return $this->handleException('Webhook processing exception', $e);
        }
    }

    /**
     * Internal error response handler.
     */
    protected function handleErrorResponse(string $message, $response): array
    {
        $this->logError($message, $response);

        return [
            'success' => false,
            'status_code' => $response->status(),
            'error' => $response->json('message') ?? $message,
            'details' => $response->json()
        ];
    }

    /**
     * Internal exception handler.
     */
    protected function handleException(string $message, \Exception $e): array
    {
        $this->logException($message, $e);

        return [
            'success' => false,
            'error' => $message,
            'exception' => $e->getMessage()
        ];
    }

    /**
     * Log API errors.
     */
    protected function logError(string $message, $response): void
    {
        Log::error("SimlessPay API Error: {$message}", [
            'status' => $response->status(),
            'body' => $response->body(),
            'url' => $response->effectiveUri(),
        ]);
    }

    /**
     * Log exceptions.
     */
    protected function logException(string $message, \Exception $e): void
    {
        Log::error("SimlessPay Service Exception: {$message}", [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
