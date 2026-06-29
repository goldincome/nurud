<?php

namespace App\Services;

use App\Models\Airport;
use App\Models\MarkupRule;

class SkyLinkResponseMapper
{
    protected static ?array $airportCache = null;

    protected function getFirstSegment(array $flight): array
    {
        return $flight['segments'][0][0] ?? $flight;
    }

    protected function getSegments(array $flight): array
    {
        return $flight['segments'][0] ?? [];
    }

    public function mapSearchResults(array $flights, array $searchData, MarkupService $markupService, SimlessPayService $simlessPayService): array
    {
        $formattedFlights = [];
        $airlineGroups = [];

        foreach ($flights as $flight) {
            $flightData = $this->mapFlightToView($flight, $searchData, $markupService, $simlessPayService);
            $formattedFlights[] = $flightData;

            $airlineCode = $flightData['airlineCode'];
            if (!isset($airlineGroups[$airlineCode])) {
                $airlineGroups[$airlineCode] = [
                    'airline' => $flightData['airline'],
                    'airlineCode' => $airlineCode,
                    'cheapestPrice' => $flightData['rawPrice'],
                    'cheapestPriceFormatted' => $flightData['price'],
                    'flights' => [],
                ];
            } elseif ($flightData['rawPrice'] < $airlineGroups[$airlineCode]['cheapestPrice']) {
                $airlineGroups[$airlineCode]['cheapestPrice'] = $flightData['rawPrice'];
                $airlineGroups[$airlineCode]['cheapestPriceFormatted'] = $flightData['price'];
            }
            $airlineGroups[$airlineCode]['flights'][] = $flightData;
        }

        usort($airlineGroups, fn($a, $b) => $a['cheapestPrice'] <=> $b['cheapestPrice']);

        return [$formattedFlights, $airlineGroups];
    }

    public function mapFlightToView(array $flight, array $searchData, MarkupService $markupService, SimlessPayService $simlessPayService): array
    {
        $seg = $this->getFirstSegment($flight);
        $segments = $this->getSegments($flight);

        $depCode = $seg['departure_code'] ?? '';
        $arrCode = $seg['arrival_code'] ?? '';

        $cityNames = $this->getAirportCityNames($depCode, $arrCode);

        $totalDuration = $seg['total_duration'] ?? $seg['duration_time'] ?? '0h 0m';
        $isDirect = count($segments) <= 1;

        $itineraries = [];
        foreach ($segments as $i => $s) {
            $itineraries[] = [
                'depTime' => $s['departure_time'] ?? '',
                'depAirport' => $s['departure_code'] ?? '',
                'depCity' => $s['departure_city'] ?? '',
                'depDate' => isset($searchData['departureDate'])
                    ? date('D, d M', strtotime($searchData['departureDate']))
                    : date('D, d M'),
                'arrTime' => $s['arrival_time'] ?? '',
                'arrAirport' => $s['arrival_code'] ?? '',
                'arrCity' => $s['arrival_city'] ?? '',
                'arrDate' => isset($searchData['departureDate'])
                    ? date('D, d M', strtotime($searchData['departureDate']))
                    : date('D, d M'),
                'duration' => $s['seg_duration'] ?? $s['duration_time'] ?? '0h 0m',
                'durationMinutes' => $this->parseDurationMinutes($s['seg_duration'] ?? $s['duration_time'] ?? ''),
                'stops' => $isDirect ? 'Direct' : ($i === 0 ? count($segments) - 1 . ' Stop' : ''),
                'stopCity' => $i > 0 ? ($s['departure_city'] ?? '') : '',
                'airlineCode' => $s['img'] ?? $s['airline'] ?? '',
                'airlineName' => $s['airline'] ?? '',
            ];
        }

        $bags = $seg['baggage'] ?? '20 kg';
        $cabinBag = $seg['cabin_baggage'] ?? '7 kg';
        $airline = $seg['airline'] ?? '';
        $airlineImg = $seg['img'] ?? $airline;
        $rules = MarkupRule::where('is_active', true)->where('currency_code', strtolower(config('currency.default_currency')))->first();

        return [
            'id' => $flight['booking_token'] ?? '',
            'airline' => $airline,
            'airlineCode' => $airlineImg,
            'price' => $flight['currency'] === "NGN" ? number_format($simlessPayService->convertNairaToPounds(
                $markupService->applyMarkup($flight['price'] ?? 0)
                )) : number_format($markupService->applyMarkup($flight['price'] ?? 0)),
            'rawPrice' => $flight['currency'] === "NGN"  ? $simlessPayService->convertNairaToPounds(
                $markupService->applyMarkup($flight['price'] ?? 0)
            ) : $markupService->applyMarkup($flight['price'] ?? 0),
            'currency' => $flight['currency'] ?? config('currency.default_currency'),
            'bags' => $bags,
            'cabinBag' => $cabinBag,
            'itineraries' => $itineraries,
            'totalDuration' => $this->parseDurationMinutes($totalDuration),
            'rawData' => json_encode($flight),
            'allOffer' => $flight,
        ];
    }

    public function buildFlightDataForViews(array $flight, array $pricingData, array $searchData, int $markupFee = 0, ?SimlessPayService $simlessPayService = null): array
    {
        $seg = $this->getFirstSegment($flight);
        $segments = $this->getSegments($flight);

        $depCode = $seg['departure_code'] ?? '';
        $arrCode = $seg['arrival_code'] ?? '';
        $cityNames = $this->getAirportCityNames($depCode, $arrCode);
        $currency = $pricingData['currency'] ?? $flight['currency'] ?? 'NGN';

        $departureDate = $searchData['departureDate'] ?? '';
        $cabin = $searchData['flightClass'] ?? 'ECONOMY';

        $carrierCode = $seg['img'] ?? $seg['airline'] ?? '';
        $carrierName = $seg['airline'] ?? '';

        $viewSegments = [];
        foreach ($segments as $s) {
            $departureAt = $this->formatDateTime(
                $s['departure_date'] ?? $departureDate,
                $s['departure_time'] ?? '00:00'
            );
            $arrivalAt = $this->formatDateTime(
                $s['arrival_date'] ?? $departureDate,
                $s['arrival_time'] ?? '00:00'
            );

            $viewSegments[] = [
                'carrier' => [
                    'iataCode' => $s['img'] ?? $s['airline'] ?? '',
                    'name' => $s['airline'] ?? '',
                ],
                'number' => $s['flight_no'] ?? '',
                'aircraft' => ['code' => ''],
                'duration' => $s['seg_duration'] ?? $s['duration_time'] ?? '0h 0m',
                'cabin' => strtoupper($cabin),
                'class' => $s['class_letter'] ?? 'Y',
                'segmentDeparture' => [
                    'at' => $departureAt,
                    'airport' => [
                        'iataCode' => $s['departure_code'] ?? '',
                        'city' => $s['departure_city'] ?? $cityNames['departure_city'] ?? '',
                        'name' => $s['departure_airport'] ?? '',
                    ],
                ],
                'segmentArrival' => [
                    'at' => $arrivalAt,
                    'airport' => [
                        'iataCode' => $s['arrival_code'] ?? '',
                        'city' => $s['arrival_city'] ?? $cityNames['arrival_city'] ?? '',
                        'name' => $s['arrival_airport'] ?? '',
                    ],
                ],
            ];
        }

        $adultCount = $searchData['travelers']['numberOfAdults'] ?? 1;
        $childCount = $searchData['travelers']['numberOfChildren'] ?? 0;
        $infantCount = $searchData['travelers']['numberOfInfants'] ?? 0;

        $verifiedPrice = $pricingData['verified_price'] ?? ($flight['price'] ?? 0);
        $basePrice = $pricingData['original_price'] ?? $verifiedPrice;
        $perPassenger = $pricingData['per_passenger'] ?? [];

        $perAdultBase = $perPassenger['adult'] ?? ($adultCount > 0 ? round($basePrice / ($adultCount + $childCount + $infantCount)) : 0);
        $perChildBase = $perPassenger['child'] ?? round($perAdultBase * 0.75);
        $perInfantBase = $perPassenger['infant'] ?? round($perAdultBase * 0.1);

        $totalBase = ($perAdultBase * $adultCount) + ($perChildBase * $childCount) + ($perInfantBase * $infantCount);

        $bags = $seg['baggage'] ?? 'Check Details';

        $travelerPricings = [];
        for ($i = 0; $i < $adultCount; $i++) {
            $travelerPricings[] = [
                'travelerType' => 'ADULT',
                'fareOption' => 'STANDARD',
                'price' => ['base' => $perAdultBase, 'total' => $perAdultBase, 'currency' => $currency],
                'fareDetailsBySegment' => [
                    ['includedCheckedBags' => $this->parseBaggage($bags)],
                ],
            ];
        }
        for ($i = 0; $i < $childCount; $i++) {
            $travelerPricings[] = [
                'travelerType' => 'CHILD',
                'fareOption' => 'STANDARD',
                'price' => ['base' => $perChildBase, 'total' => $perChildBase, 'currency' => $currency],
                'fareDetailsBySegment' => [
                    ['includedCheckedBags' => $this->parseBaggage($bags)],
                ],
            ];
        }
        for ($i = 0; $i < $infantCount; $i++) {
            $travelerPricings[] = [
                'travelerType' => 'HELD_INFANT',
                'fareOption' => 'STANDARD',
                'price' => ['base' => $perInfantBase, 'total' => $perInfantBase, 'currency' => $currency],
                'fareDetailsBySegment' => [
                    ['includedCheckedBags' => $this->parseBaggage($bags)],
                ],
            ];
        }

        $totalDuration = $seg['total_duration'] ?? $seg['duration_time'] ?? '0h 0m';

        return [
            'itineraries' => [
                [
                    'segments' => $viewSegments,
                    'duration' => $totalDuration,
                    'itineraryTitle' => 'Outbound' ,
                ],
            ],
            'travelerPricings' => $travelerPricings,
            'verifiedPrice' => ['total' => $verifiedPrice],
            'verifiedPriceBreakdown' => [
                'taxesAndFees' => ($verifiedPrice - $totalBase) + $markupFee,
            ],
            'rawPrice' => $verifiedPrice,
            'airline' => $carrierName,
            'airlineCode' => $carrierCode,
            'currency' => $currency,
            'bags' => $bags,
        ];
    }

    protected function parseBaggage(string $baggage): array
    {
        if (preg_match('/(\d+)\s*x\s*(\d+)\s*kg/i', $baggage, $m)) {
            return ['quantity' => (int) $m[1], 'weight' => (int) $m[2], 'weightUnit' => 'kg'];
        }
        if (preg_match('/(\d+)\s*bag/i', $baggage, $m)) {
            return ['quantity' => (int) $m[1], 'weight' => 23, 'weightUnit' => 'kg'];
        }
        if (preg_match('/(\d+)\s*kg/i', $baggage, $m)) {
            return ['quantity' => 1, 'weight' => (int) $m[1], 'weightUnit' => 'kg'];
        }
        return ['quantity' => 1, 'weight' => 23, 'weightUnit' => 'kg'];
    }

    public function buildReservePayload(string $bookingToken, array $pricingData, array $request, array $searchData): array
    {
        $travelersPayload = [];
        $primaryGuest = null;
        $adultCount = 0;
        $childCount = 0;
        $infantCount = 0;

        $phone = preg_replace('/[^0-9]/', '', $request['phone'] ?? '');
        $countryCode = preg_replace('/[^0-9]/', '', $request['countryCallingCode'] ?? '234');

        foreach ($request['passengers'] as $typeKey => $passenger) {
            try {
                $dob = \Carbon\Carbon::parse($passenger['dob'])->format('Y-m-d');
            } catch (\Exception $e) {
                $dob = '1990-06-10';
            }

            $travelerData = [
                'title' => $passenger['title'] ?? 'Mr',
                'first_name' => $passenger['firstName'],
                'last_name' => $passenger['surname'],
                'email' => $request['email'] ?? '',
                'phone' => $phone,
                'country_code' => $countryCode,
                'dob' => $dob,
                'gender' => match ($passenger['gender']) {
                    '2' => 'female',
                    '3' => 'male',
                    default => 'male',
                },
                'passport_number' => $passenger['passport_number'] ?? 'A12345678',
                'passport_expiry' => $passenger['passport_expiry'] ?? '2030-01-01',
                'passport_issue_date' => $passenger['passport_issue_date'] ?? '2020-01-01',
                'nationality' => $passenger['nationality'] ?? 'NG',
            ];

            if (str_starts_with((string) $typeKey, 'adult_')) {
                $key = 'adult_' . $adultCount;
                $adultCount++;
                if ($primaryGuest === null) {
                    $primaryGuest = $travelerData;
                }
            } elseif (str_starts_with((string) $typeKey, 'child_')) {
                $key = 'child_' . $childCount;
                $childCount++;
            } else {
                $key = 'infant_' . $infantCount;
                $infantCount++;
            }

            $travelersPayload[$key] = $travelerData;
        }

        return [
            'booking_token' => $bookingToken,
            'passengers' => [
                'adults' => $adultCount,
                'children' => $childCount,
                'infants' => $infantCount,
            ],
            'travellers' => [
                'primary_guest' => $primaryGuest,
                'travelers' => $travelersPayload,
            ],
            'ticket_time_limit_hours' => 48,
        ];
    }

    protected function formatDateTime(string $date, string $time): string
    {
        $time = trim($time);
        if (preg_match('/^(\d{1,2}):(\d{2})\s*(am|pm)$/i', $time, $m)) {
            $hour = (int) $m[1];
            $min = (int) $m[2];
            $ampm = strtolower($m[3]);
            if ($ampm === 'pm' && $hour < 12) $hour += 12;
            if ($ampm === 'am' && $hour === 12) $hour = 0;
            $time = sprintf('%02d:%02d', $hour, $min);
        }

        $date = trim($date);
        $formats = ['d-m-Y', 'Y-m-d', 'm/d/Y', 'd/m/Y'];
        $parsed = null;
        foreach ($formats as $fmt) {
            $d = \DateTime::createFromFormat($fmt, $date);
            if ($d !== false) {
                $parsed = $d->format('Y-m-d');
                break;
            }
        }
        if ($parsed === null) {
            $parsed = date('Y-m-d', strtotime($date)) ?: $date;
        }

        return $parsed . 'T' . $time . ':00';
    }

    protected function parseDurationMinutes(string $duration): int
    {
        if (preg_match('/(\d+)h\s*(\d+)m/', $duration, $m)) {
            return ((int) $m[1] * 60) + (int) $m[2];
        }
        if (preg_match('/(\d+)h/', $duration, $m)) {
            return (int) $m[1] * 60;
        }
        if (preg_match('/(\d+)m/', $duration, $m)) {
            return (int) $m[1];
        }
        return 0;
    }

    protected function getAirportCityNames(string ...$codes): array
    {
        $codes = array_filter($codes);
        if (empty($codes)) {
            return ['departure_city' => '', 'arrival_city' => ''];
        }

        if (self::$airportCache === null) {
            self::$airportCache = Airport::whereIn('code', $codes)
                ->pluck('city', 'code')
                ->toArray();
        }

        return [
            'departure_city' => self::$airportCache[$codes[0]] ?? '',
            'arrival_city' => self::$airportCache[$codes[1] ?? ''] ?? '',
        ];
    }
}
