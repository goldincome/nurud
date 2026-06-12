<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SkyLinkAuthService;
use App\Services\SkyLinkApiService;
use App\Services\SkyLinkResponseMapper;
use App\Services\MarkupService;
use App\Services\SimlessPayService;

class TestSkyLinkE2E extends Command
{
    protected $signature = 'skylink:test-e2e';
    protected $description = 'End-to-end test of SkyLink API integration';

    public function handle()
    {
        $this->info("=== SkyLink E2E Test ===\n");

        // Step 1: Auth
        $this->info("1. Testing authentication...");
        $authService = app(SkyLinkAuthService::class);
        $token = $authService->getAccessToken();
        if (!$token) {
            $this->error("FAIL: Could not obtain JWT token");
            return 1;
        }
        $this->info("   Token obtained: " . substr($token, 0, 20) . "...");

        // Step 2: Search flights
        $this->info("\n2. Testing flight search (LHR→LOS, 1 Adult, Economy)...");
        $apiService = app(SkyLinkApiService::class);
        $searchParams = [
            'originLocationCode' => 'LHR',
            'destinationLocationCode' => 'LOS',
            'departureDate' => now()->addDays(30)->format('Y-m-d'),
            'returnDate' => null,
            'travelers' => [
                'numberOfAdults' => 1,
                'numberOfChildren' => 0,
                'numberOfInfants' => 0,
            ],
            'flightClass' => 'ECONOMY',
            'routeModel' => 0,
        ];

        try {
            $searchResult = $apiService->searchFlights($searchParams);
            $flights = $searchResult['data']['flights'] ?? [];

            if (empty($flights)) {
                $this->warn("   No flights found. This may be acceptable if no inventory for this route/date.");
                $this->warn("   Full response: " . json_encode($searchResult));
                return 0;
            }

            $this->info("   Found " . count($flights) . " flights");
            $firstFlight = $flights[0];
            $this->info("   First flight: " . ($firstFlight['airline'] ?? '?') . " " . ($firstFlight['flight_no'] ?? '?'));
            $this->info("   Departure: " . ($firstFlight['departure_time'] ?? '?') . " → Arrival: " . ($firstFlight['arrival_time'] ?? '?'));
            $this->info("   Price: " . ($firstFlight['price'] ?? '?') . " " . ($firstFlight['currency'] ?? ''));
            $this->info("   Booking token: " . substr($firstFlight['booking_token'] ?? 'N/A', 0, 30) . "...");
        } catch (\Exception $e) {
            $this->error("FAIL: Search error - " . $e->getMessage());
            return 1;
        }

        // Step 3: Verify/pricing
        $this->info("\n3. Testing flight pricing verification...");
        try {
            $pricingResult = $apiService->verifyPrice($firstFlight);
            $pricingData = $pricingResult['data'] ?? [];

            $this->info("   Verified price: " . ($pricingData['verified_price'] ?? 'N/A'));
            $this->info("   Expires at: " . ($pricingData['expires_at'] ?? 'N/A'));

            if (empty($pricingData['booking_token'])) {
                $this->warn("   WARNING: No booking_token in pricing response");
            } else {
                $this->info("   Booking token: " . substr($pricingData['booking_token'], 0, 30) . "...");
            }
        } catch (\Exception $e) {
            $this->error("FAIL: Pricing error - " . $e->getMessage());
            return 1;
        }

        // Step 4: Test ResponseMapper search result formatting
        $this->info("\n4. Testing response mapper (search results → view format)...");
        try {
            $mapper = app(SkyLinkResponseMapper::class);
            $markupService = app(MarkupService::class);
            $simlessPayService = app(SimlessPayService::class);

            list($formattedFlights, $airlineGroups) = $mapper->mapSearchResults(
                $flights,
                $searchParams,
                $markupService,
                $simlessPayService
            );

            $this->info("   Formatted " . count($formattedFlights) . " flights for view");
            if (count($formattedFlights) > 0) {
                $f = $formattedFlights[0];
                $this->info("   Has itineraries: " . (isset($f['itineraries']) ? 'YES (' . count($f['itineraries']) . ')' : 'NO'));
                $this->info("   Has rawPrice: " . (isset($f['rawPrice']) ? 'YES (' . $f['rawPrice'] . ')' : 'NO'));
                $this->info("   Has airline: " . (isset($f['airline']) ? 'YES' : 'NO'));
                $this->info("   Has bags: " . (isset($f['bags']) ? 'YES' : 'NO'));
                $this->info("   Has cabinBag: " . (isset($f['cabinBag']) ? 'YES' : 'NO'));
                $this->info("   Has totalDuration: " . (isset($f['totalDuration']) ? 'YES (' . $f['totalDuration'] . ' min)' : 'NO'));
                $this->info("   Has allOffer: " . (isset($f['allOffer']) ? 'YES' : 'NO'));
            }
            $this->info("   Airline groups: " . count($airlineGroups));
        } catch (\Exception $e) {
            $this->error("FAIL: Mapper error - " . $e->getMessage());
            return 1;
        }

        // Step 5: Test buildFlightDataForViews
        $this->info("\n5. Testing buildFlightDataForViews (for booking.blade.php)...");
        try {
            $viewData = $mapper->buildFlightDataForViews(
                $firstFlight,
                $pricingData,
                $searchParams,
                5000, // markup fee
                $simlessPayService
            );

            $this->info("   Has itineraries: " . (isset($viewData['itineraries']) ? 'YES (' . count($viewData['itineraries']) . ')' : 'NO'));
            if (isset($viewData['itineraries'][0])) {
                $seg = $viewData['itineraries'][0]['segments'][0] ?? null;
                $this->info("   Segment departure city: " . ($seg['segmentDeparture']['airport']['city'] ?? 'N/A'));
                $this->info("   Segment arrival city: " . ($seg['segmentArrival']['airport']['city'] ?? 'N/A'));
                $this->info("   Carrier: " . ($seg['carrier']['name'] ?? 'N/A'));
                $this->info("   Cabin: " . ($seg['cabin'] ?? 'N/A'));
            }
            $this->info("   Traveler pricings: " . (isset($viewData['travelerPricings']) ? 'YES (' . count($viewData['travelerPricings']) . ')' : 'NO'));
            $this->info("   Verified price total: " . ($viewData['verifiedPrice']['total'] ?? 'N/A'));
        } catch (\Exception $e) {
            $this->error("FAIL: View data builder error - " . $e->getMessage());
            return 1;
        }

        // Step 6: Test buildReservePayload
        $this->info("\n6. Testing buildReservePayload...");
        try {
            $mockRequest = new \Illuminate\Http\Request();
            $mockRequest->merge([
                'email' => 'test@example.com',
                'phone' => '+2348012345678',
                'countryCallingCode' => '234',
                'passengers' => [
                    'adult_1' => [
                        'title' => 'Mr',
                        'firstName' => 'John',
                        'surname' => 'Doe',
                        'middleName' => '',
                        'dob' => '1990-01-15',
                        'gender' => '3',
                    ],
                ],
            ]);

            $reservePayload = $mapper->buildReservePayload(
                $pricingData['booking_token'] ?? $firstFlight['booking_token'],
                $pricingData,
                $mockRequest,
                $searchParams
            );

            $this->info("   Booking token set: " . (isset($reservePayload['booking_token']) ? 'YES' : 'NO'));
            $this->info("   Passengers: {$reservePayload['passengers']['adults']} adults, {$reservePayload['passengers']['children']} children, {$reservePayload['passengers']['infants']} infants");
            $this->info("   Primary guest: " . ($reservePayload['travellers']['primary_guest']['first_name'] ?? 'N/A'));
            $this->info("   Fare summary verified_price: " . ($reservePayload['fare_summary']['verified_price'] ?? 'N/A'));
        } catch (\Exception $e) {
            $this->error("FAIL: Reserve payload builder error - " . $e->getMessage());
            return 1;
        }

        $this->info("\n=== ALL TESTS PASSED ===");
        return 0;
    }
}
