<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchFlightRequest;
use App\Models\Country;
use App\Services\MarkupService;
use App\Services\SimlessPayService;
use App\Services\SkyLinkApiService;
use App\Services\SkyLinkResponseMapper;
use App\Services\VerifiedPriceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    protected SkyLinkApiService $skyLinkService;
    protected SkyLinkResponseMapper $responseMapper;
    protected VerifiedPriceService $verifiedPriceService;

    public function __construct(SkyLinkApiService $skyLinkService, 
    SkyLinkResponseMapper $responseMapper, VerifiedPriceService $verifiedPriceService)
    {
        $this->skyLinkService = $skyLinkService;
        $this->responseMapper = $responseMapper;
        $this->verifiedPriceService = $verifiedPriceService;
    }

    public function search(SearchFlightRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            set_time_limit(300);

            $result = $this->skyLinkService->searchFlights($validated);
            
            $flights = $result['data']['flights'] ?? [];
            $meta = $result['data']['meta'] ?? [];
           
            $searchId = Str::uuid()->toString();
          
            Cache::put('flight_search_' . $searchId, [
                'flights' => $flights,
                'meta' => $meta,
                'search_data' => $validated,
            ], now()->addMinutes(14));

            session()->put('current_search_id', $searchId);
            session()->put('last_flight_search', $validated);

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

        if (!$searchResults) {
            return redirect()->route('search')->with('error', 'Search results expired. Please search again.');
        }

        $rawFlights = $searchResults['flights'] ?? [];
        $searchData = $searchResults['search_data'] ?? [];
        $routeModel = $searchData['routeModel'] ?? 0;

        /*dd($rawFlights[21], $rawFlights[0]['price'] , app(MarkupService::class)->applyMarkup($rawFlights[0]['price'] ?? 0), number_format(app(SimlessPayService::class)->convertNairaToPounds(
                app(MarkupService::class)->applyMarkup($rawFlights[0]['price'] ?? 0)
            )), app(SimlessPayService::class)->getCachedExchangeRate(2)
            );
        */
        list($formattedFlights, $airlineGroups) = $this->responseMapper->mapSearchResults(
            $rawFlights,
            $searchData,
            $markupService,
            $simlessPayService
        );

        $origin = !empty($formattedFlights)
            ? ($formattedFlights[0]['itineraries'][0]['depCity'] ?? 'Origin')
            : 'Origin';
        $destination = !empty($formattedFlights)
            ? ($formattedFlights[0]['itineraries'][0]['arrCity'] ?? 'Destination')
            : 'Destination';
        $tripDate = $searchData['departureDate'] ?? now()->format('Y-m-d');
        $returnDate = $searchData['returnDate'] ?? null;
        $travelers = $searchData['travelers'] ?? [];
        $travelersCount = ($travelers['numberOfAdults'] ?? 1) + ($travelers['numberOfChildren'] ?? 0) + ($travelers['numberOfInfants'] ?? 0);
        $flightClass = ucfirst(strtolower(str_replace('_', ' ', $searchData['flightClass'] ?? 'ECONOMY')));

        $airlines = collect($rawFlights)
            ->groupBy(fn($f) => $f['segments'][0][0]['img'] ?? $f['segments'][0][0]['airline'] ?? '')
            ->map(fn($group, $code) => [
                'code' => $code,
                'name' => $group->first()['segments'][0][0]['airline'] ?? $code,
            ])
            ->values()
            ->toArray();

        return view('search-result', [
            'flights' => $formattedFlights,
            'airlineGroups' => $airlineGroups,
            'origin' => $origin,
            'destination' => $destination,
            'tripDate' => date('D, M d', strtotime($tripDate)),
            'returnDate' => $returnDate ? date('D, M d', strtotime($returnDate)) : null,
            'travelersCount' => $travelersCount,
            'flightClass' => $flightClass,
            'tripType' => $routeModel,
            'airlines' => $airlines,
            'routeModel' => $routeModel,
        ]);
    }

    public function verifyOffer(Request $request, MarkupService $markupService): RedirectResponse
    {
        $validated = $request->validate(['allOffer' => 'required|string']);

        $decodedJson = urldecode($validated['allOffer']);
        $offer = json_decode($decodedJson, true);
        //dd($offer, $offer['segments'][0][0]['currency'], session('last_flight_search'));
        if (!$offer || !isset($offer['booking_token'])) {
            Log::error('SkyLink verify: missing booking_token in offer', ['offer' => $offer]);
            return back()->with('error', 'Invalid flight offer data. Please search again.');
        }
        $passengers = [
            'adults' => session('last_flight_search.travelers.numberOfAdults', 1),
            'children' => session('last_flight_search.travelers.numberOfChildren', 0),
            'infants' => session('last_flight_search.travelers.numberOfInfants', 0),
        ];      
        $maxAttempts = 3;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $pricingResult = $this->skyLinkService->verifyPrice($offer, $passengers);
               
                $pricingData = $pricingResult['data'] ?? [];

                $verifyId = Str::uuid()->toString();
               
                session()->put('current_verify_id', $verifyId);
                //use verified price for price change 
                $verifiedPrice = $this->verifiedPriceService->getVerifiedPrice($pricingData);
                $markUpFee = $markupService->getMarkupFee($verifiedPrice);

                
                $searchId = session()->get('current_search_id');
                $searchData = Cache::get('flight_search_' . $searchId)['search_data'] ?? [];

                //$verifiedPrice = $this->verifiedPriceService->getVerifiedPrice($pricingData);
                $total = $verifiedPrice + $markUpFee;
                // $estimatedTax =  //round($verifiedPrice * 0.15) + $markupFee;

                $flightData = $this->responseMapper->buildFlightDataForViews(
                    $offer,
                    $pricingData,
                    $searchData,
                    $markUpFee,
                    app(SimlessPayService::class)
                );

                 Cache::put('verified_offer_' . $verifyId, [
                    'pricing' => $pricingData,
                    'originalOffer' => $offer,
                    'total' => $total,
                    'flightData' => $flightData,
                    'searchData' => $searchData,
                    'verifiedPrice' => $verifiedPrice,
                ], now()->addMinutes(20));
                
                //dd($pricingResult, $fData, $passengers, $verifiedPrice);
                session()->put('markup_fee', $markUpFee);
                //dd($offer, $pricingData['price_changed'], json_encode($pricingData), number_format($markupService->applyMarkup( $verifiedPrice)));
                return redirect()->route('bookings.create')->with([
                    'success' => 'Flight offer verified successfully.',
                ]);
            } catch (\Exception $e) {
                Log::error("Verification attempt {$attempt}/{$maxAttempts} failed: " . $e->getMessage());
                if ($attempt < $maxAttempts) {
                    usleep(500_000);
                    continue;
                }
                return redirect()->route('search.results')->with('error', 'We could not verify the price from the airline at the moment. Please try again later and choose one of the airlines below.');
            }
        }
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $min = $minutes % 60;
        return "{$hours}h {$min}m";
    }
}
