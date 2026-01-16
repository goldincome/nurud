<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentWebhookController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Stripe webhook events
     */
    public function handleStripe(Request $request): JsonResponse
    {
        try {
            // Get the raw payload and signature
            $payload = $request->getContent();
            $signature = $request->header('Stripe-Signature');

            if (empty($signature)) {
                Log::warning('Missing Stripe signature in webhook request');
                return response()->json(['error' => 'Missing signature'], 400);
            }

            // Process the webhook using the PaymentService
            $result = $this->paymentService->handleWebhook($payload, $signature);

            if ($result['status'] === 'success') {
                return response()->json(['status' => 'success']);
            } elseif ($result['status'] === 'error') {
                Log::error('Webhook processing error', ['message' => $result['message'] ?? 'Unknown error']);
                return response()->json(['error' => $result['message']], 500);
            } elseif ($result['status'] === 'ignored') {
                return response()->json(['status' => 'ignored']);
            } else {
                return response()->json(['status' => 'failed'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Stripe webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Webhook processing failed',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Handle bank transfer manual verification (for financial admin)
     */
    public function verifyBankTransfer(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booking_id' => 'required|integer|exists:bookings,id',
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $result = $this->paymentService->verifyBankTransfer(
                $request->booking_id,
                $request->user_id
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bank transfer verified successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Bank transfer verification failed'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Bank transfer verification failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification process failed'
            ], 500);
        }
    }

    /**
     * Get bank transfer instructions for a booking
     */
    public function getBankTransferInstructions(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booking_id' => 'required|integer|exists:bookings,id',
            ]);

            $instructions = $this->paymentService->getBankTransferInstructions();

            return response()->json([
                'success' => true,
                'instructions' => $instructions
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get bank transfer instructions', [
                'error' => $e->getMessage(),
                'booking_id' => $request->booking_id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get transfer instructions'
            ], 500);
        }
    }

    /**
     * Test webhook endpoint (for development)
     */
    public function test(Request $request): JsonResponse
    {
        if (config('app.env') !== 'local') {
            return response()->json(['error' => 'Test endpoint only available in local environment'], 403);
        }

        Log::info('Test webhook received', ['data' => $request->all()]);

        return response()->json([
            'status' => 'test_success',
            'received_data' => $request->all(),
            'timestamp' => now()->toISOString()
        ]);
    }
}
