<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Traveler;
use App\Models\Payment;
use App\Mail\BookingConfirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingService
{
    protected FlexiApiService $flexiService;

    public function __construct(FlexiApiService $flexiService)
    {
        $this->flexiService = $flexiService;
    }

    public function createPendingBooking(array $bookingData, array $travelersData): Booking
    {
        return DB::transaction(function () use ($bookingData, $travelersData) {
            // Generate unique PNR
            $pnr = $this->generatePnr();

            // Create booking
            $booking = Booking::create([
                'user_id' => $bookingData['user_id'] ?? null,
                'pnr_reference' => $pnr,
                'guest_email' => $bookingData['guest_email'],
                'guest_phone' => $bookingData['guest_phone'] ?? null,
                'flight_offer_id' => $bookingData['flight_offer_id'],
                'total_amount' => $bookingData['total_amount'],
                'currency' => $bookingData['currency'] ?? 'NGN',
                'status' => 'pending_payment',
                'expires_at' => now()->addHours(24),
            ]);

            // Call Flexi API to reserve
            try {
                $apiResponse = $this->flexiService->reserveFlight($bookingData['flight_offer_id'], $travelersData);

                // Update booking with API response if needed
                $booking->update([
                    'flight_offer_id' => $apiResponse['offer_id'] ?? $bookingData['flight_offer_id'],
                ]);

                Log::info('Flight reserved via API', ['pnr' => $pnr, 'offer_id' => $bookingData['flight_offer_id']]);
            } catch (\Exception $e) {
                // If API fails, delete the booking and rethrow
                $booking->delete();
                Log::error('Failed to reserve flight via API', ['pnr' => $pnr, 'error' => $e->getMessage()]);
                throw new \Exception('Unable to reserve flight at this time. Please try again.');
            }

            // Create travelers
            foreach ($travelersData as $travelerData) {
                $booking->travelers()->create([
                    'first_name' => $travelerData['first_name'],
                    'last_name' => $travelerData['last_name'],
                    'dob' => $travelerData['dob'],
                    'gender' => $travelerData['gender'],
                    'passport_number' => $travelerData['passport_number'],
                    'passport_expiry' => $travelerData['passport_expiry'],
                    'type' => $travelerData['type'] ?? 'ADULT',
                ]);
            }

            return $booking;
        });
    }

    public function confirmPayment(Booking $booking): bool
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status !== 'pending_payment') {
                throw new \Exception('Booking cannot be confirmed in current status');
            }

            try {
                // Issue ticket via API
                $apiResponse = $this->flexiService->issueTicket($booking->pnr_reference);

                // Update booking
                $booking->update([
                    'status' => 'confirmed',
                    'ticket_issued_at' => now(),
                ]);

                Log::info('Ticket issued successfully', ['pnr' => $booking->pnr_reference]);

                return true;
            } catch (\Exception $e) {
                Log::error('Failed to issue ticket', ['pnr' => $booking->pnr_reference, 'error' => $e->getMessage()]);
                throw new \Exception('Payment confirmed but ticket issuance failed. Contact support.');
            }
        });
    }

    public function cancelBooking(Booking $booking, string $reason = null): void
    {
        DB::transaction(function () use ($booking, $reason) {
            $booking->update([
                'status' => 'cancelled',
            ]);

            Log::info('Booking cancelled', [
                'pnr' => $booking->pnr_reference,
                'reason' => $reason
            ]);
        });
    }

    public function expireBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'expired',
            ]);

            // TODO: Send expiration email

            Log::info('Booking expired automatically', [
                'pnr' => $booking->pnr_reference,
            ]);
        });
    }

    protected function generatePnr(): string
    {
        do {
            $pnr = Str::upper(Str::random(6));
        } while (Booking::where('pnr_reference', $pnr)->exists());

        return $pnr;
    }

    public function getBookingByPnr(string $pnr, string $email): ?Booking
    {
        return Booking::where('pnr_reference', $pnr)
            ->where('guest_email', $email)
            ->with(['travelers', 'user'])
            ->first();
    }
}
