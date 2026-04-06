<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Traveler;
use App\Models\Payment;
use App\Mail\BookingConfirmed;
use App\Services\SimlessPayService;
use App\Models\PriceInPounds;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingService
{
    protected FlexiApiService $flexiService;
    protected SimlessPayService $simlessPayService;

    public function __construct(FlexiApiService $flexiService, SimlessPayService $simlessPayService)
    {
        $this->flexiService = $flexiService;
        $this->simlessPayService = $simlessPayService;
    }

    public function createPendingBooking(array $bookingData): Booking
    {
        return DB::transaction(function () use ($bookingData) {
            $order = $bookingData['flightOrder'];

            // 1. Create Booking
            $booking = Booking::create([
                'id' => $order['id'] ?? null,
                'user_id' => auth()->id() ?? null,
                'office_id' => $order['officeId'] ?? null,
                'ota_id' => $order['otaId'] ?? null,
                'flight_offer_id' => $order['flightOfferId'] ?? null,
                'origin_location' => $order['originLocation'] ?? null,
                'origin_destination' => $order['originDestination'] ?? null,
                'carrier_code' => $order['carrierCode'] ?? null,
                'route_model' => $order['routeModel'] ?? null,
                'departure_date' => $order['departureDate'] ?? null,
                'cabin' => $order['cabin'] ?? null,
                'class' => $order['class'] ?? null,
                'ama_client_ref' => $order['amaClientRef'] ?? null,
                'reservation_id' => $order['reservationId'] ?? null,
                'base_price' => $order['basePrice'] ?? 0,
                'taxes_and_fees' => $order['taxesAndFees'] ?? 0,
                'total_price' => $order['totalPrice'] ?? 0,
                'markup_fee' => session()->get('markup_fee') ?? 0,
                'contact_phone' => $order['contactPhone'] ?? null,
                'customer_first_name' => $order['customerFirstName'] ?? null,
                'customer_last_name' => $order['customerLastName'] ?? null,
                'customer_email' => $order['customerEmail'] ?? null,
                'reservation_date' => $order['reservationDate'] ?? null,
                'order_status' => $order['orderStatus'] ?? 5,
                'date_created' => $order['dateCreated'] ?? null,
                'date_modified' => $order['dateModified'] ?? null,
                'currency' => $order['currency'] ?? 'NGN',
                'status' => BookingStatus::PENDING_PAYMENT,
                'expires_at' => now()->addHours(24),
            ]);

            // Save prices in pounds
            $booking->priceInPounds()->create([
                'currency' => 'GBP',
                'price' => $this->simlessPayService->convertNairaToPounds($booking->base_price),
                'tax' => $this->simlessPayService->convertNairaToPounds($booking->taxes_and_fees),
                'markup' => $this->simlessPayService->convertNairaToPounds($booking->markup_fee),
                'total_price' => $this->simlessPayService->convertNairaToPounds($booking->total_price),
            ]);

            // 2. Create Travelers
            if (isset($order['otaTravelers']) && is_array($order['otaTravelers'])) {
                foreach ($order['otaTravelers'] as $traveler) {
                    $booking->travelers()->create([
                        'id' => $traveler['id'] ?? null,
                        'first_name' => $traveler['firstName'] ?? null,
                        'last_name' => $traveler['lastName'] ?? null,
                        'base_price' => $traveler['basePrice'] ?? 0,
                        'taxes_and_fees' => $traveler['taxesAndFees'] ?? 0,
                        'total_price' => $traveler['totalPrice'] ?? 0,
                        'gender' => $traveler['gender'] ?? null,
                        'email' => $traveler['email'] ?? null,
                        'phone' => $traveler['phone'] ?? null,
                        'country_calling_code' => $traveler['countryCallingCode'] ?? null,
                        'date_of_birth' => $traveler['dateOfBirth'] ?? null,
                        'traveler_id' => $traveler['travelerId'] ?? null,
                        'date_created' => $traveler['dateCreated'] ?? null,
                        'date_modified' => $traveler['dateModified'] ?? null,
                    ]);
                }
            }

            // 3. Create Itineraries
            if (isset($bookingData['itineraries']) && is_array($bookingData['itineraries'])) {
                foreach ($bookingData['itineraries'] as $itinerary) {
                    $booking->itineraries()->create([
                        'itinerary_title' => $itinerary['itineraryTitle'] ?? null,
                        'itinerary_summary' => $itinerary['itinerarySummary'] ?? null,
                        'itinerary_index' => $itinerary['itineraryIndex'] ?? null,
                        'duration' => $itinerary['duration'] ?? null,
                        'duration_in_minutes' => $itinerary['durationInMinutes'] ?? null,
                        'segments' => $itinerary['segments'] ?? [],
                    ]);
                }
            }

            // 4. Create Traveler Pricings
            if (isset($bookingData['travelerPricings']) && is_array($bookingData['travelerPricings'])) {
                foreach ($bookingData['travelerPricings'] as $pricing) {
                    $booking->travelerPricings()->create([
                        'traveler_id' => $pricing['travelerId'] ?? null,
                        'fare_option' => $pricing['fareOption'] ?? null,
                        'traveler_type' => $pricing['travelerType'] ?? null,
                        'price' => $pricing['price'] ?? [],
                        'price_breakdown' => $pricing['priceBreakdown'] ?? [],
                        'fare_details_by_segment' => $pricing['fareDetailsBySegment'] ?? [],
                    ]);
                }
            }

            // Log::info('New booking recorded in database', [
            //     'booking_id' => $booking->id,
            //     'reservation_id' => $booking->reservation_id,
            //     'customer' => $booking->customer_email
            // ]);

            return $booking;
        });
    }

    public function confirmPayment(Booking $booking): bool
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status !== BookingStatus::PENDING_PAYMENT) {
                throw new \Exception('Booking cannot be confirmed in current status');
            }

            try {
                // Issue ticket via API
                $apiResponse = $this->flexiService->issueTicket($booking->reservation_id);

                // Update booking
                $booking->update([
                    'status' => BookingStatus::CONFIRMED,
                    'ticket_issued_at' => now(),
                ]);

                Log::info('Ticket issued successfully', ['reservation_id' => $booking->reservation_id]);

                return true;
            } catch (\Exception $e) {
                Log::error('Failed to issue ticket', ['reservation_id' => $booking->reservation_id, 'error' => $e->getMessage()]);
                throw new \Exception('Payment confirmed but ticket issuance failed. Contact support.');
            }
        });
    }

    public function cancelBooking(Booking $booking, string $reason = null): void
    {
        DB::transaction(function () use ($booking, $reason) {
            $booking->update([
                'status' => BookingStatus::CANCELLED,
            ]);

            Log::info('Booking cancelled', [
                'reservation_id' => $booking->reservation_id,
                'reason' => $reason
            ]);
        });
    }

    public function expireBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => BookingStatus::EXPIRED,
            ]);

            // TODO: Send expiration email

            Log::info('Booking expired automatically', [
                'reservation_id' => $booking->reservation_id,
            ]);
        });
    }

    protected function generatePnr(): string
    {
        do {
            $pnr = Str::upper(Str::random(6));
        } while (Booking::where('reservation_id', $pnr)->exists());

        return $pnr;
    }

    public function getBookingByPnr(string $pnr, string $email): ?Booking
    {
        return Booking::where('reservation_id', $pnr)
            ->where('customer_email', $email)
            ->with(['travelers', 'user'])
            ->first();
    }
}
