<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Airport;
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
use App\Enums\PaymentStatus;
use App\Services\FlexiApiService;
use App\Jobs\SendPaymentConfirmationWithTicket;
use App\Services\AdminNotificationService;

class BookingService
{
    protected FlexiApiService $flexiService;
    protected SimlessPayService $simlessPayService;
    protected VerifiedPriceService $verifiedPriceService;

    public function __construct(FlexiApiService $flexiService, 
    SimlessPayService $simlessPayService, VerifiedPriceService $verifiedPriceService)
    {
        $this->flexiService = $flexiService;
        $this->simlessPayService = $simlessPayService;
        $this->verifiedPriceService = $verifiedPriceService;
    }

    public function createPendingBooking(array $bookingData, array $bookingOffer): Booking
    {  
  
        return DB::transaction(function () use ($bookingData, $bookingOffer) {
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
                'offer_data' => $bookingOffer,
            ]);

            // Save prices in pounds
            $booking->priceInPounds()->create([
                'currency' => 'GBP',
                'price' =>  $bookingData['fare_summary']['price_changed']  ? $bookingData['fare_summary']['verified_price'] : $bookingData['fare_summary']['original_price'], //$this->simlessPayService->convertNairaToPounds($booking->base_price),
                'tax' => number_format($this->simlessPayService->convertNairaToPounds($bookingOffer['offerInfo']['verifiedPriceBreakdown']['taxesAndFees'] + session()->get('markup_fee'))),
                'markup' => $this->simlessPayService->convertNairaToPounds(session()->get('markup_fee')),
                'total_price' => number_format($this->simlessPayService->convertNairaToPounds($bookingOffer['offerInfo']['verifiedPriceBreakdown']['total'] + session()->get('markup_fee'))),
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

            // Notify admin of new reservation
            AdminNotificationService::notifyNewReservation($booking);

            return $booking;
        });
    }

    public function createPendingBookingFromSkyLinkPayload(array $payload, array $flightSummary = [], array $searchParams = []): Booking
    {
        return DB::transaction(function () use ($payload, $flightSummary, $searchParams) {
            $fareSummary = $payload['fare_summary'] ?? [];
            $contact = $payload['travellers']['primary_guest'] ?? [];
            $flight = $flightSummary ?: ($payload['flight_summary'] ?? []);
            $passengers = $payload['passengers'] ?? [];
            $travelers = $payload['travellers']['travelers'] ?? [];
            //dd($payload);
            $adultCount = $passengers['adults'] ?? 1;
            $childCount = $passengers['children'] ?? 0;
            $infantCount = $passengers['infants'] ?? 0;

            $verifiedPrice = $this->verifiedPriceService->getVerifiedPrice($fareSummary);
            $basePrice = $fareSummary['original_price'] ?? $verifiedPrice;
            $markupFee = (int) session()->get('markup_fee', 0);
            $totalPrice = $payload['total_price'] ?? ($verifiedPrice + $markupFee);
            $groupTotalPrice = $payload['group_total_price'] ?? ($verifiedPrice + $markupFee);
            $currency = $fareSummary['currency'] ?? 'NGN';

            $perPassenger = $fareSummary['per_passenger'] ?? [];
            $perAdultBase = $perPassenger['adult'] ?? ($adultCount > 0 ? round($basePrice / max(1, $adultCount + $childCount + $infantCount)) : 0);
            $perChildBase = $perPassenger['child'] ?? round($perAdultBase * 0.75);
            $perInfantBase = $perPassenger['infant'] ?? round($perAdultBase * 0.1);
            $totalBase = ($perAdultBase * $adultCount) + ($perChildBase * $childCount) + ($perInfantBase * $infantCount);

            $allSegs = $flight['segments'][0] ?? ($flight['segments'] ?? []);
            if (!empty($allSegs) && is_array($allSegs)) {
                $firstSeg = $allSegs[0];
                $lastSeg = end($allSegs);
                $originLocation = $firstSeg['departure_code'] ?? ($flight['departure_code'] ?? $searchParams['originLocationCode'] ?? null);
                $originDestination = $lastSeg['arrival_code'] ?? ($flight['arrival_code'] ?? $searchParams['originDestinationCode'] ?? null);
                $seg = $firstSeg;
            } else {
                $seg = $flight;
                $originLocation = $seg['departure_code'] ?? ($flight['departure_code'] ?? $searchParams['originLocationCode'] ?? null);
                $originDestination = $seg['arrival_code'] ?? ($flight['arrival_code'] ?? $searchParams['originDestinationCode'] ?? null);
            }
            $carrierCode = $seg['img'] ?? $seg['airline'] ?? ($flight['airline'] ?? null);

            $booking = Booking::create([
                'user_id' => auth()->id() ?? null,
                'flight_offer_id' => $payload['booking_token'] ?? null,
                'origin_location' => $originLocation,
                'origin_destination' => $originDestination,
                'carrier_code' => $carrierCode,
                'route_model' => $searchParams['routeModel'] ?? 0,
                'departure_date' => $searchParams['departureDate'] ?? null,
                'cabin' => $seg['class'] ?? $flight['class'] ?? 'Economy',
                'class' => $seg['class'] ?? $flight['class'] ?? 'Economy',
                'base_price' => $verifiedPrice,
                'total_price' => $totalPrice,
                'markup_fee' => $markupFee,
                'contact_phone' => $contact['phone'] ?? null,
                'customer_first_name' => $contact['first_name'] ?? '',
                'customer_last_name' => $contact['last_name'] ?? '',
                'customer_email' => $contact['email'] ?? null,
                'currency' => $currency,
                'status' => BookingStatus::PENDING_PAYMENT,
                'expires_at' => now()->addHours(24),
                'offer_data' => $payload,
                'skylink_data' => [
                    'booking_token' => $payload['booking_token'] ?? null,
                    'verified_price' => $verifiedPrice,
                    'passenger_counts' => $passengers,
                ],
            ]);
           

            $theTax = ($totalPrice - $groupTotalPrice);
            //dd(number_format($this->simlessPayService->convertNairaToPounds($theTax),0, '.', ''), $groupTotalPrice, $theTax, $totalPrice, $markupFee);
            $booking->priceInPounds()->create([
                'currency' => 'GBP',
                'price' =>  number_format($this->simlessPayService->convertNairaToPounds($groupTotalPrice),0, '.', ''),
                'tax' =>  number_format($this->simlessPayService->convertNairaToPounds($theTax),0, '.', ''),
                'markup' => number_format($this->simlessPayService->convertNairaToPounds($markupFee),0, '.', ''),
                'total_price' => number_format($this->simlessPayService->convertNairaToPounds($totalPrice),0, '.', ''),
            ]);

            foreach ($travelers as $key => $traveler) {
                $type = match (true) {
                    str_starts_with((string) $key, 'adult') => 'ADULT',
                    str_starts_with((string) $key, 'child') => 'CHILD',
                    str_starts_with((string) $key, 'infant') => 'HELD_INFANT',
                    default => 'ADULT',
                };

                $perBase = match ($type) {
                    'ADULT' => $perAdultBase,
                    'CHILD' => $perChildBase,
                    'HELD_INFANT' => $perInfantBase,
                    default => $perAdultBase,
                };

                $gender = match ($traveler['gender'] ?? '') {
                    'female' => 2,
                    'male' => 3,
                    default => 1,
                };

                $booking->travelers()->create([
                    'first_name' => $traveler['first_name'] ?? '',
                    'last_name' => $traveler['last_name'] ?? '',
                    'gender' => $gender,
                    'email' => $traveler['email'] ?? $contact['email'] ?? null,
                    'phone' => $traveler['phone'] ?? $contact['phone'] ?? null,
                    'date_of_birth' => $traveler['dob'] ?? null,
                ]);

                $booking->travelerPricings()->create([
                    'traveler_id' => $key,
                    'traveler_type' => $type,
                    'fare_option' => 'STANDARD',
                    'price' => ['base' => $perBase, 'total' => $perBase, 'currency' => $currency],
                ]);
            }

            $allItineraries = $flight['segments'] ?? [];
            if (empty($allItineraries)) { $allItineraries = [[$flight]]; }

            $routeModel = $searchParams['routeModel'] ?? 0;

            foreach ($allItineraries as $itinIndex => $itinSegments) {
                if (empty($itinSegments)) continue;

                $itinFirst = $itinSegments[0];
                $itinLast = end($itinSegments);

                $itinOrigin = $itinFirst['departure_code'] ?? $originLocation ?? '';
                $itinDestination = $itinLast['arrival_code'] ?? $originDestination ?? '';

                $builtSegments = [];
                $totalDurationMins = 0;

                foreach ($itinSegments as $s) {
                    $depDate = $s['departure_date'] ?? $searchParams['departureDate'] ?? '';
                    $depTime = $s['departure_time'] ?? '00:00';
                    $arrDate = $s['arrival_date'] ?? $searchParams['departureDate'] ?? '';
                    $arrTime = $s['arrival_time'] ?? '00:00';

                    try {
                        $depAt = \Carbon\Carbon::parse($depDate)->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($depTime)->format('H:i:s');
                    } catch (\Exception $e) {
                        $depAt = $depDate . 'T' . $depTime;
                    }
                    try {
                        $arrAt = \Carbon\Carbon::parse($arrDate)->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($arrTime)->format('H:i:s');
                    } catch (\Exception $e) {
                        $arrAt = $arrDate . 'T' . $arrTime;
                    }

                    $segDur = $s['seg_duration'] ?? $s['duration_time'] ?? '';
                    if (preg_match('/(\d+)h\s*(\d+)m/', $segDur, $m)) {
                        $totalDurationMins += ((int) $m[1] * 60) + (int) $m[2];
                    } elseif (preg_match('/(\d+)h/', $segDur, $m)) {
                        $totalDurationMins += (int) $m[1] * 60;
                    } elseif (preg_match('/(\d+)m/', $segDur, $m)) {
                        $totalDurationMins += (int) $m[1];
                    }

                    $builtSegments[] = [
                        'carrier' => [
                            'iataCode' => $s['img'] ?? $s['airline'] ?? $carrierCode,
                            'name' => $s['airline'] ?? $carrierCode,
                        ],
                        'number' => $s['flight_no'] ?? '',
                        'duration' => $segDur,
                        'segmentDeparture' => [
                            'at' => $depAt,
                            'airport' => [
                                'iataCode' => $s['departure_code'] ?? $itinOrigin ?: '',
                                'city' => $s['departure_city'] ?? ($s['departure_code'] ?? $itinOrigin ?: ''),
                            ],
                        ],
                        'segmentArrival' => [
                            'at' => $arrAt,
                            'airport' => [
                                'iataCode' => $s['arrival_code'] ?? $itinDestination ?: '',
                                'city' => $s['arrival_city'] ?? ($s['arrival_code'] ?? $itinDestination ?: ''),
                            ],
                        ],
                    ];
                }

                $itinDuration = $totalDurationMins > 0
                    ? sprintf('%dh %dm', floor($totalDurationMins / 60), $totalDurationMins % 60)
                    : ($itinFirst['total_duration'] ?? null);

                $itinIndexNum = $itinIndex + 1;
                $itinTitle = match (true) {
                    $routeModel === 1 && $itinIndex === 0 => 'Outbound',
                    $routeModel === 1 && $itinIndex === 1 => 'Return',
                    default => 'Flight ' . $itinIndexNum,
                };

                $originDisplay = \App\Models\Booking::resolveAirportDisplay($itinOrigin);
                $destinationDisplay = \App\Models\Booking::resolveAirportDisplay($itinDestination);

                $booking->itineraries()->create([
                    'itinerary_title' => $itinTitle,
                    'itinerary_summary' => $originDisplay . ' to ' . $destinationDisplay,
                    'itinerary_index' => $itinIndexNum,
                    'duration' => $itinDuration,
                    'segments' => $builtSegments,
                ]);
            }

            AdminNotificationService::notifyNewReservation($booking);

            return $booking;
        });
    }

    public function confirmBookingAndIssueTicket(Booking $booking): bool
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status !== BookingStatus::PENDING_PAYMENT) {
                throw new \Exception('Booking cannot be confirmed in current status');
            }

            try {
                // Issue ticket via API
                $result = app(FlexiApiService::class)->issueTicket($booking->offer_data);
                if (isset($result) && $result['flightOrder']['pnr']) {

                    $payment = Payment::where('booking_id', $booking->id)
                        ->first();

                    if ($payment) {
                        $payment->update([
                            'status' => PaymentStatus::COMPLETED,
                        ]);
                    }

                    // Update booking status
                    $booking->update([
                        'status' => BookingStatus::CONFIRMED,
                        'ticket_issued_at' => now(),
                        'pnr' => $result['flightOrder']['pnr'],
                    ]);

                    // Send payment confirmation email
                    dispatch(new SendPaymentConfirmationWithTicket($booking));

                }

                Log::info('Ticket issued successfully', ['reservation_id' => $booking->reservation_id]);

                return true;
            } catch (\Exception $e) {
                Log::error('Failed to issue ticket', ['reservation_id' => $booking->reservation_id, 'error' => $e->getMessage()]);
                throw new \Exception('Payment confirmed but ticket issuance failed. Contact support.' . $e->getMessage());
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
