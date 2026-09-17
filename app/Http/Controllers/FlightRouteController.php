<?php

namespace App\Http\Controllers;

use App\Models\FlightRoute;
use App\Services\AirportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FlightRouteController extends Controller
{
    public function __construct(
        private readonly AirportService $airportService,
    ) {}

    public function show(string $routeSlug): View
    {
        $data = Cache::remember("pseo_flight_route_{$routeSlug}", now()->addHours(2), function () use ($routeSlug) {
            $route = FlightRoute::where('slug', $routeSlug)
                ->where('is_indexable', true)
                ->firstOrFail();

            return $this->buildRouteDataPayload($route);
        });

        $this->wireBookingContext($routeSlug, $data['search_data'] ?? []);

        return view('flights.show', ['page' => $data]);
    }

    private function buildRouteDataPayload(FlightRoute $route): array
    {
        $airports = $this->airportService->lookup();
        $meta = $route->search_intent_metadata ?? [];
        $origin = $airports->get($route->origin_code);
        $destination = $airports->get($route->destination_code);

        $originCity = $this->cityFrom($origin['state_name'] ?? $route->origin_code);
        $destinationCity = $meta['destination_city']
            ?? $this->cityFrom($destination['state_name'] ?? $route->destination_code);

        $originName = $origin['name'] ?? $route->origin_code;
        $destinationName = $destination['name'] ?? $route->destination_code;

        $faqs = $meta['faqs'] ?? [];
        $airlines = $meta['airlines_operating'] ?? [];

        $liveFares = $route->liveFares()
            ->orderBy('price', 'asc')
            ->limit(5)
            ->get();

        $firstFare = $liveFares->first();

        $payload = [
            'meta' => [
                'title' => "Cheap Flights from {$originCity} ({$route->origin_code}) to {$destinationCity} ({$route->destination_code}) | From £{$route->lowest_fare_gbp} | Nurud Travels",
                'description' => "Compare flight deals from {$originCity} to {$destinationCity}. " .
                    ($route->has_direct_flights ? 'Direct flights available. ' : 'Affordable connecting options. ') .
                    "Lowest fare from £{$route->lowest_fare_gbp}. Book securely with Nurud Travels.",
                'canonical' => route('flights.route.show', ['route_slug' => $route->slug]),
                'robots' => 'index, follow',
            ],
            'route' => [
                'slug' => $route->slug,
                'origin_city' => $originCity,
                'origin_code' => $route->origin_code,
                'origin_airport' => $originName,
                'destination_city' => $destinationCity,
                'destination_code' => $route->destination_code,
                'destination_airport' => $destinationName,
                'distance' => $route->distance_miles ? "{$route->distance_miles} miles" : null,
                'distance_raw' => $route->distance_miles,
                'duration' => $route->avg_duration_minutes
                    ? floor($route->avg_duration_minutes / 60) . 'h ' . ($route->avg_duration_minutes % 60) . 'm'
                    : null,
                'has_direct' => $route->has_direct_flights,
                'lowest_fare' => $route->lowest_fare_gbp,
                'currency' => 'GBP',
                'primary_airline' => $route->primary_airline,
            ],
            'airlines_operating' => $airlines,
            'faqs' => $faqs,
            'seasonal_tips' => $meta['seasonal_tips'] ?? [],
            'live_fares' => $liveFares->map(fn ($fare) => [
                'id' => $fare->id,
                'airline' => $fare->airline,
                'airline_code' => $fare->airline_code,
                'price' => $fare->payload['price'] ?? number_format((float) $fare->price, 2),
                'raw_price' => (float) $fare->price,
                'currency' => $fare->currency,
                'bags' => $fare->payload['bags'] ?? null,
                'cabin_bag' => $fare->payload['cabinBag'] ?? null,
                'itineraries' => $this->enrichItineraryCities($fare->payload['itineraries'] ?? [], $airports),
                'all_offer' => $fare->all_offer,
            ])->values()->toArray(),
            'search_data' => [
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
                'departureDate' => $firstFare?->searched_for_departure_date
                    ?? Carbon::today()->addDays(5)->format('Y-m-d'),
                'returnDate' => $firstFare?->searched_for_return_date
                    ?? Carbon::today()->addDays(12)->format('Y-m-d'),
            ],
        ];

        $payload['schema'] = $this->generateJsonLdSchema($route, $payload);

        return $payload;
    }

    private function enrichItineraryCities(array $itineraries, $airports): array
    {
        foreach ($itineraries as &$leg) {
            if (empty($leg['depCity'])) {
                $leg['depCity'] = $this->cityFrom(
                    $airports->get(strtoupper((string) $leg['depAirport']))['state_name'] ?? $leg['depAirport'] ?? ''
                );
            }
            if (empty($leg['arrCity'])) {
                $leg['arrCity'] = $this->cityFrom(
                    $airports->get(strtoupper((string) $leg['arrAirport']))['state_name'] ?? $leg['arrAirport'] ?? ''
                );
            }
        }

        return $itineraries;
    }

    /**
     * Populate the same search-session context the live search flow uses so
     * the on-page "Book Now" buttons resolve real pricing via verifyOffer.
     *
     * Runs on every request (outside the page cache) so Book Now keeps working
     * even when the 2h page cache is warm.
     */
    private function wireBookingContext(string $routeSlug, array $searchData): void
    {
        if (empty($searchData)) {
            return;
        }

        $searchId = 'pseo_' . md5($routeSlug . $searchData['departureDate'] . $searchData['returnDate']);

        Cache::put('flight_search_' . $searchId, [
            'flights' => [],
            'meta' => [],
            'search_data' => $searchData,
        ], now()->addMinutes(14));

        session()->put('current_search_id', $searchId);
        session()->put('last_flight_search', $searchData);
    }

    private function generateJsonLdSchema(FlightRoute $route, array $payload): array
    {
        $graph = [
            [
                '@type' => 'Flight',
                'provider' => [
                    '@type' => 'TravelAgency',
                    'name' => 'Nurud Travels',
                    'url' => config('app.url'),
                ],
                'departureAirport' => [
                    '@type' => 'Airport',
                    'name' => $payload['route']['origin_airport'],
                    'iataCode' => $payload['route']['origin_code'],
                ],
                'arrivalAirport' => [
                    '@type' => 'Airport',
                    'name' => $payload['route']['destination_airport'],
                    'iataCode' => $payload['route']['destination_code'],
                ],
            ],
            [
                '@type' => 'AggregateOffer',
                'priceCurrency' => 'GBP',
                'lowPrice' => $payload['route']['lowest_fare'],
                'offerCount' => count($payload['airlines_operating']) ?: 1,
                'url' => route('flights.route.show', ['route_slug' => $route->slug]),
            ],
        ];

        if (!empty($payload['faqs'])) {
            $graph[] = [
                '@type' => 'FAQPage',
                'mainEntity' => array_map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                ], $payload['faqs']),
            ];
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }

    private function cityFrom(string $value): string
    {
        return trim(explode(',', $value)[0]);
    }
}