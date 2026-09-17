<?php

namespace App\Console\Commands;

use App\Models\FlightRoute;
use App\Models\FlightRouteLiveFare;
use App\Services\MarkupService;
use App\Services\SimlessPayService;
use App\Services\SkyLinkApiService;
use App\Services\SkyLinkResponseMapper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateFlightRoutePrices extends Command
{
    protected $signature = 'flight-routes:update-prices';
    protected $description = 'Run a real SkyLink search per flight route and persist the cheapest live fares 3x daily';

    public function __construct(
        private readonly SkyLinkApiService $skyLinkApiService,
        private readonly SkyLinkResponseMapper $responseMapper,
        private readonly MarkupService $markupService,
        private readonly SimlessPayService $simlessPayService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $routes = FlightRoute::where('is_indexable', true)->get();

        if ($routes->isEmpty()) {
            $this->warn('No indexable flight routes found.');
            return Command::SUCCESS;
        }

        foreach ($routes as $route) {
            $this->updateRoute($route);
        }

        $this->info("Updated {$routes->count()} flight route(s).");
        return Command::SUCCESS;
    }

    private function updateRoute(FlightRoute $route): void
    {
        $searchData = $this->buildSearchData($route);

        try {
            $result = $this->skyLinkApiService->searchFlights($searchData);
        } catch (\Throwable $e) {
            Log::warning('flight-routes:update-prices search threw', ['slug' => $route->slug, 'error' => $e->getMessage()]);
            $this->warn("Search threw for {$route->slug}, skipping.");
            return;
        }

        $rawFlights = $result['data']['flights'] ?? [];

        if (empty($rawFlights)) {
            $this->warn("No flights returned for {$route->slug}, keeping previous fares.");
            return;
        }

        try {
            [$formattedFlights, $airlineGroups] = $this->responseMapper->mapSearchResults(
                $rawFlights,
                $searchData,
                $this->markupService,
                $this->simlessPayService
            );
        } catch (\Throwable $e) {
            Log::warning('flight-routes:update-prices mapping threw', ['slug' => $route->slug, 'error' => $e->getMessage()]);
            $this->warn("Mapping failed for {$route->slug}, keeping previous fares.");
            return;
        }

        $formattedFlights = collect($formattedFlights)->sortBy('rawPrice')->values();

        $topFares = $formattedFlights->take(10);

        if ($topFares->isEmpty()) {
            $this->warn("No mapped fares for {$route->slug}.");
            return;
        }

        $cheapest = $topFares->first();

        DB::transaction(function () use ($route, $topFares, $airlineGroups, $searchData, $formattedFlights, $cheapest) {
            $route->liveFares()->delete();

            foreach ($topFares as $index => $fare) {
                $firstItinerary = $fare['itineraries'][0] ?? [];
                $returnItinerary = $fare['itineraries'][1] ?? [];

                $route->liveFares()->create([
                    'airline' => $fare['airline'] ?? null,
                    'airline_code' => $fare['airlineCode'] ?? null,
                    'price' => $fare['rawPrice'] ?? 0,
                    'currency' => $fare['currency'] ?? config('currency.default_currency', 'GBP'),
                    'stops' => $this->stopsToInt($firstItinerary['stops'] ?? null),
                    'duration_minutes' => $fare['totalDuration'] ?? $firstItinerary['durationMinutes'] ?? null,
                    'departure_code' => $firstItinerary['depAirport'] ?? null,
                    'departure_time' => $firstItinerary['depTime'] ?? null,
                    'departure_date' => $searchData['departureDate'] ?? null,
                    'arrival_code' => $firstItinerary['arrAirport'] ?? null,
                    'arrival_time' => $firstItinerary['arrTime'] ?? null,
                    'arrival_date' => $searchData['returnDate'] ?? null,
                    'all_offer' => $fare['allOffer'] ?? null,
                    'payload' => $fare,
                    'searched_for_departure_date' => $searchData['departureDate'] ?? null,
                    'searched_for_return_date' => $searchData['returnDate'] ?? null,
                    'sort_order' => $index,
                ]);
            }

            $route->update([
                'lowest_fare_gbp' => $cheapest['rawPrice'] ?? $route->lowest_fare_gbp,
                'primary_airline' => $cheapest['airline'] ?? $route->primary_airline,
                'search_intent_metadata' => $this->mergeAirlinesMetadata($route, $formattedFlights, $cheapest),
            ]);

            Cache::forget("pseo_flight_route_{$route->slug}");
        });

        $this->info("Updated {$route->slug}: cheapest £{$cheapest['rawPrice']} via {$cheapest['airline']}.");
    }

    private function buildSearchData(FlightRoute $route): array
    {
        return [
            'routeModel' => 1,
            'flightClass' => 'ECONOMY',
            'directFlightOnly' => false,
            'dateWindow' => false,
            'travelers' => [
                'numberOfAdults' => 1,
                'numberOfChildren' => 0,
                'numberOfInfants' => 0,
            ],
            'originLocationCode' => $route->origin_code,
            'originDestinationCode' => $route->destination_code,
            'departureDate' => Carbon::today()->addDays(5)->format('Y-m-d'),
            'returnDate' => Carbon::today()->addDays(12)->format('Y-m-d'),
        ];
    }

    private function mergeAirlinesMetadata(FlightRoute $route, $formattedFlights, array $cheapest): array
    {
        $existing = $route->search_intent_metadata ?? [];

        $airlines = [];
        foreach ($formattedFlights as $fare) {
            $code = $fare['airlineCode'] ?? 'UNK';
            $name = $fare['airline'] ?? $code;
            if (isset($airlines[$code])) {
                $airlines[$code]['min_price'] = min($airlines[$code]['min_price'], $fare['rawPrice'] ?? PHP_INT_MAX);
                continue;
            }
            $firstItinerary = $fare['itineraries'][0] ?? [];
            $airlines[$code] = [
                'name' => $name,
                'flight_type' => ($firstItinerary['stops'] ?? 'Direct') === 'Direct' ? 'Direct' : 'Connecting',
                'min_price' => $fare['rawPrice'] ?? 0,
            ];
        }

        $existing['airlines_operating'] = array_values($airlines);
        $existing['last_live_update'] = now()->toDateTimeString();

        return $existing;
    }

    private function stopsToInt(?string $stops): ?int
    {
        if ($stops === null || $stops === '' || $stops === 'Direct') {
            return 0;
        }
        $match = preg_match('/(\d+)/', $stops, $m);
        return $match ? (int) $m[1] : null;
    }
}