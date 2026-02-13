<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\FlexiApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SearchFlightRequest;

class SearchController extends Controller
{
    protected FlexiApiService $flexiService;

    public function __construct(FlexiApiService $flexiService)
    {
        $this->flexiService = $flexiService;
    }

    public function search(SearchFlightRequest $request): RedirectResponse
    { 
        //dd($request->all());
        $validated = $request->validated();
        if($validated['routeModel'] == 0 || $validated['routeModel'] == 2){
            $validated['dateWindow'] = false;
        }
        //dd($validated);
        try {

            $flights = $this->flexiService->searchFlights($validated);
            
            // Store search results in session for results page
            session()->put('search_results', [
                'flights' => $flights,
                'search_data' => $validated,
            ]);

            return redirect()->route('search.results');
        } catch (\Exception $e) {
            
            return back()->with('error', 'Flight search failed. Please try again.'.$e->getMessage());
        }
    }
   public function results(Request $request)
    {
        $searchResults = session()->get('search_results');

        if (!$searchResults) {
            return redirect()->route('/')->with('error', 'No search results found.');
        }

        // 1. GET ROUTE MODEL (0 = One Way, 1 = Round Trip, 2 = Multi City)
        $routeModel = $searchResults['search_data']['routeModel'] ?? 0;

        $rawOffers = $searchResults['flights']['offerInfos'] ?? [];
        $formattedFlights = [];
        $airlineGroups = [];
        
        foreach ($rawOffers as $offer) {
            $offerItineraries = [];
            //dd($offer);
            // Basic Validation
            $firstItinerarySegments = $offer['itineraries'][0]['segments'] ?? [];
            if (empty($firstItinerarySegments)) continue; 

            $mainAirlineCode = $firstItinerarySegments[0]['carrier']['iataCode'] ?? $offer['validatingAirlineCodes'][0];
            $mainAirlineName = $firstItinerarySegments[0]['carrier']['name'] ?? $offer['validatingAirlineCodes'][0];

            // Loop through ALL itineraries (Legs)
            foreach ($offer['itineraries'] as $index => $itinerary) {
                $segments = $itinerary['segments'];
                $firstSegment = $segments[0];
                $lastSegment = end($segments);
                
                $stopCount = count($segments) - 1;
                $stopsText = $stopCount === 0 ? 'Direct' : ($stopCount . ' Stop' . ($stopCount > 1 ? 's' : ''));
                
                $stopCity = '';
                if ($stopCount > 0) {
                    $stopCity = $firstSegment['segmentArrival']['airport']['city'] ?? $firstSegment['segmentArrival']['airport']['iataCode'];
                }

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

            // Baggage Info
            $bags = 'Check Details';
            $baggageInfo = $offer['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags'] ?? null;
            if ($baggageInfo) {
                if (isset($baggageInfo['quantity'])) {
                    $bags = $baggageInfo['quantity'] . ' Bags';
                } elseif (isset($baggageInfo['weight'])) {
                    $bags = $baggageInfo['weight'] . ' ' . ($baggageInfo['weightUnit'] ?? 'KG');
                }
            }

            $formattedFlights[] = [
                'id' => $offer['id'],
                'airline' => $mainAirlineName,
                'airlineCode' => $mainAirlineCode,
                'price' => number_format($offer['price']['total']),
                'rawPrice' => $offer['price']['total'],
                'currency' => $offer['price']['currency'] ?? 'NGN',
                'bags' => $bags,
                'itineraries' => $offerItineraries, 
                'totalDuration' => $offer['durationInMinutes'] ?? 0,
                'rawData' => json_encode($offer),
                'allOffer' => $offer //do not remove
            ];

            // Group by airline for matrix display
            if (!isset($airlineGroups[$mainAirlineCode])) {
                $airlineGroups[$mainAirlineCode] = [
                    'airline' => $mainAirlineName,
                    'airlineCode' => $mainAirlineCode,
                    'cheapestPrice' => $offer['price']['total'],
                    'cheapestPriceFormatted' => number_format($offer['price']['total']),
                    'flights' => []
                ];
            } else {
                // Update cheapest price if current offer is cheaper
                if ($offer['price']['total'] < $airlineGroups[$mainAirlineCode]['cheapestPrice']) {
                    $airlineGroups[$mainAirlineCode]['cheapestPrice'] = $offer['price']['total'];
                    $airlineGroups[$mainAirlineCode]['cheapestPriceFormatted'] = number_format($offer['price']['total']);
                }
            }
            $airlineGroups[$mainAirlineCode]['flights'][] = end($formattedFlights);
        }

        // Sort airlines by cheapest price (ascending)
        usort($airlineGroups, function($a, $b) {
            return $a['cheapestPrice'] - $b['cheapestPrice'];
        });

        // Header Info
        $origin = 'Origin';
        $destination = 'Destination';
        if (!empty($formattedFlights)) {
            $origin = $formattedFlights[0]['itineraries'][0]['depCity'];
            // For Multi-City header, show last city
            $destination = end($formattedFlights[0]['itineraries'])['arrCity'];
        }

        $tripDate = $searchResults['search_data']['departure_date'] ?? now()->format('Y-m-d');

        return view('search-result', [
            'flights' => $formattedFlights,
            'airlineGroups' => $airlineGroups,
            'origin' => $origin,
            'destination' => $destination,
            'tripDate' => date('D, M d', strtotime($tripDate)),
            'tripType' => $searchResults['search_data']['routeModel'] ?? 'Flight',
            'airlines' => $searchResults['flights']['airlines'] ?? [],
            'routeModel' => $routeModel // <--- PASSING ROUTE MODEL TO VIEW
        ]);
    }

    // Helper to format minutes to "Xh Ym"
    private function formatDuration($minutes) {
        $hours = floor($minutes / 60);
        $min = $minutes % 60;
        return "{$hours}h {$min}m";
    }
    public function apiSearch(Request $request): JsonResponse
    {
        $request->validate([
            'departure_iata' => 'required|string|size:3',
            'arrival_iata' => 'required|string|size:3',
            'travel_date' => 'required|date|after:today',
        ]);

        try {
            $searchParams = [
                'departure' => $request->departure_iata,
                'arrival' => $request->arrival_iata,
                'date' => $request->travel_date,
                'currency' => 'NGN',
            ];

            $flights = $this->flexiService->searchFlights($searchParams);

            return response()->json([
                'success' => true,
                'flights' => $flights,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Flight search failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function verifyOffer(Request $request): RedirectResponse
    {     
        // Validate the request
        $validated = $request->validate([ 
            'allOffer' => 'required|string' 
        ]);
        
        $data = json_decode($validated['allOffer'], true);
        
        try {
            $flightOutput = $this->flexiService->verifyPrice($data);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid flight offer data.');
            }

            // Store the verified flight offer in session
            session()->put('verified_flight_offer', $flightOutput);

            // Redirect to booking page
            return redirect()->route('bookings.create')->with([
                'success' => 'Flight offer verified successfully. Please complete your booking.',
            ]);

        } catch (\Exception $e) {
            Log::error('Flight offer verification failed: ' . $e->getMessage());
            return back()->with('error', 'Flight offer verification failed. Please try again.');
        }
    }
}
