<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\FlexiApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SearchFlightRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\MarkupService;
use App\Services\SimlessPayService;

class SearchController extends Controller
{
    protected FlexiApiService $flexiService;

    public function __construct(FlexiApiService $flexiService)
    {
        $this->flexiService = $flexiService;
    }

    public function search(SearchFlightRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['routeModel'] == 0 || $validated['routeModel'] == 2) {
            $validated['dateWindow'] = false;
        }

        try {
            // Fix for the 30s timeout error
            set_time_limit(120);

            $flights = $this->flexiService->searchFlights($validated);

            // Generate a unique ID to avoid stuffing the SQL Session
            $searchId = Str::uuid()->toString();

            // Store the heavy data in Cache (expires in 60 mins)
            Cache::put('flight_search_' . $searchId, [
                'flights' => $flights,
                'search_data' => $validated,
            ], now()->addMinutes(60));

            // Store only the reference ID in the session
            session()->put('current_search_id', $searchId);

            return redirect()->route('search.results');
        } catch (\Exception $e) {
            Log::error("Search Error: " . $e->getMessage());
            return back()->with('error', 'Flight search failed: ' . $e->getMessage());
        }
    }

    public function results(Request $request, MarkupService $markupService, SimlessPayService $simlessPayService)
    {
        $searchId = session()->get('current_search_id');
        $searchResults = Cache::get('flight_search_' . $searchId);

        //dd($searchResults);

        if (!$searchResults) {
            return redirect()->route('search')->with('error', 'Search results expired. Please search again.');
        }

        $routeModel = $searchResults['search_data']['routeModel'] ?? 0;
        $rawOffers = $searchResults['flights']['offerInfos'] ?? [];
        $formattedFlights = [];
        $airlineGroups = [];

        foreach ($rawOffers as $offer) {
            $offerItineraries = [];

            $firstItinerarySegments = $offer['itineraries'][0]['segments'] ?? [];
            if (empty($firstItinerarySegments))
                continue;

            $mainAirlineCode = $firstItinerarySegments[0]['carrier']['iataCode'] ?? $offer['validatingAirlineCodes'][0];
            $mainAirlineName = $firstItinerarySegments[0]['carrier']['name'] ?? $offer['validatingAirlineCodes'][0];

            foreach ($offer['itineraries'] as $itinerary) {
                $segments = $itinerary['segments'];
                $firstSegment = $segments[0];
                $lastSegment = end($segments);

                $stopCount = count($segments) - 1;
                $stopsText = $stopCount === 0 ? 'Direct' : ($stopCount . ' Stop' . ($stopCount > 1 ? 's' : ''));

                $stopCity = $stopCount > 0 ? ($firstSegment['segmentArrival']['airport']['city'] ?? $firstSegment['segmentArrival']['airport']['iataCode']) : '';

                $offerItineraries[] = [
                    'depTime' => date('H:i', strtotime($firstSegment['segmentDeparture']['at'])),
                    'depAirport' => $firstSegment['segmentDeparture']['airport']['iataCode'],
                    'depCity' => $firstSegment['segmentDeparture']['airport']['city'] ?? $firstSegment['segmentDeparture']['airport']['name'],
                    'depDate' => date('D, d M', strtotime($firstSegment['segmentDeparture']['at'])),
                    'arrTime' => date('H:i', strtotime($lastSegment['segmentArrival']['at'])),
                    'arrAirport' => $lastSegment['segmentArrival']['airport']['iataCode'],
                    'arrCity' => $lastSegment['segmentArrival']['airport']['city'] ?? $lastSegment['segmentArrival']['airport']['name'],
                    'arrDate' => date('D, d M', strtotime($lastSegment['segmentArrival']['at'])),
                    'duration' => $this->formatDuration($itinerary['durationInMinutes'] ?? 0),
                    'durationMinutes' => $itinerary['durationInMinutes'] ?? 0,
                    'stops' => $stopsText,
                    'stopCity' => $stopCity,
                    'airlineCode' => $firstSegment['carrier']['iataCode'] ?? '',
                    'airlineName' => $firstSegment['carrier']['name'] ?? '',
                ];
            }

            $bags = 'Check Details';
            $baggageInfo = $offer['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags'] ?? null;
            if ($baggageInfo) {
                $bags = isset($baggageInfo['quantity']) ? $baggageInfo['quantity'] . ' Bags' : ($baggageInfo['weight'] . ' ' . ($baggageInfo['weightUnit'] ?? 'KG'));
            }

            $flightData = [
                'id' => $offer['id'],
                'airline' => $mainAirlineName,
                'airlineCode' => $mainAirlineCode,
                'price' => number_format($simlessPayService->convertNairaToPounds($markupService->applyMarkup($offer['priceBreakdown']['total']))),
                'rawPrice' => $simlessPayService->convertNairaToPounds($markupService->applyMarkup($offer['priceBreakdown']['total'])),
                'currency' => $offer['price']['currency'] ?? 'NGN',
                'bags' => $bags,
                'itineraries' => $offerItineraries,
                'totalDuration' => $offer['durationInMinutes'] ?? 0,
                'rawData' => json_encode($offer),
                'allOffer' => $offer
            ];

            $formattedFlights[] = $flightData;

            if (!isset($airlineGroups[$mainAirlineCode])) {
                $airlineGroups[$mainAirlineCode] = [
                    'airline' => $mainAirlineName,
                    'airlineCode' => $mainAirlineCode,
                    'cheapestPrice' => $simlessPayService->convertNairaToPounds($markupService->applyMarkup($offer['priceBreakdown']['total'])),
                    'cheapestPriceFormatted' => number_format($simlessPayService->convertNairaToPounds($markupService->applyMarkup($offer['priceBreakdown']['total']))),
                    'flights' => []
                ];
            } elseif ($offer['price']['total'] < $airlineGroups[$mainAirlineCode]['cheapestPrice']) {
                $airlineGroups[$mainAirlineCode]['cheapestPrice'] = $simlessPayService->convertNairaToPounds($markupService->applyMarkup($offer['priceBreakdown']['total']));
                $airlineGroups[$mainAirlineCode]['cheapestPriceFormatted'] = number_format($simlessPayService->convertNairaToPounds($markupService->applyMarkup($offer['priceBreakdown']['total'])));
            }
            $airlineGroups[$mainAirlineCode]['flights'][] = $flightData;
        }

        usort($airlineGroups, fn($a, $b) => $a['cheapestPrice'] <=> $b['cheapestPrice']);

        $origin = !empty($formattedFlights) ? ($formattedFlights[0]['itineraries'][0]['depCity'] ?? 'Origin') : 'Origin';
        $destination = !empty($formattedFlights) ? ($formattedFlights[0]['itineraries'][0]['arrCity'] ?? 'Destination') : 'Destination';
        $tripDate = $searchResults['search_data']['departureDate'] ?? now()->format('Y-m-d');
        
        $returnDate = $searchResults['search_data']['returnDate'] ?? null;
        
        $travelers = $searchResults['search_data']['travelers'] ?? [];
        $travelersCount = ($travelers['numberOfAdults'] ?? 1) + ($travelers['numberOfChildren'] ?? 0) + ($travelers['numberOfInfants'] ?? 0);
        
        $flightClass = $searchResults['search_data']['flightClass'] ?? 'ECONOMY';
        $flightClass = ucfirst(strtolower(str_replace('_', ' ', $flightClass)));
        
        //dd($formattedFlights);
        return view('search-result', [
            'flights' => $formattedFlights,
            'airlineGroups' => $airlineGroups,
            'origin' => $origin,
            'destination' => $destination,
            'tripDate' => date('D, M d', strtotime($tripDate)),
            'returnDate' => $returnDate ? date('D, M d', strtotime($returnDate)) : null,
            'travelersCount' => $travelersCount,
            'flightClass' => $flightClass,
            'tripType' => $searchResults['search_data']['routeModel'] ?? 'Flight',
            'airlines' => $searchResults['flights']['airlines'] ?? [],
            'routeModel' => $routeModel
        ]);
    }

    public function verifyOffer(Request $request, MarkupService $markupService): RedirectResponse
    {
        $validated = $request->validate(['allOffer' => 'required|string']);

        $decodedJson = urldecode($validated['allOffer']);
        // Convert JSON string to PHP Array
        $data = json_decode($decodedJson, true);

        try {
            $flightOutput = $this->flexiService->verifyPrice($data);
            //Get Markeup Fee and put it in session
            session()->put('markup_fee', $markupService->getMarkupFee($flightOutput['verifiedPrice']['total']));

            // Move large verified offer to Cache instead of Session
            $verifyId = Str::uuid()->toString();
            Cache::put('verified_offer_' . $verifyId, $flightOutput, now()->addMinutes(20));
            session()->put('current_verify_id', $verifyId);
            //dd($flightOutput['verifiedPrice']['total']);
            return redirect()->route('bookings.create')->with([
                'success' => 'Flight offer verified successfully.',
            ]);

        } catch (\Exception $e) {
            Log::error('Verification failed: ' . $e->getMessage());
            return back()->with('error', 'Flight offer verification failed.');
        }
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $min = $minutes % 60;
        return "{$hours}h {$min}m";
    }
}