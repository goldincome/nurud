<?php

use App\Models\FlightRoute;
use App\Services\SkyLinkApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

afterEach(function () {
    Cache::flush();
});

it('renders live fare cards when fares exist and no-index is absent', function () {
    $route = FlightRoute::create([
        'origin_code' => 'LHR',
        'destination_code' => 'LOS',
        'slug' => 'test-lhr-to-lagos-los',
        'lowest_fare_gbp' => 463.00,
        'is_indexable' => true,
    ]);

    $route->liveFares()->create([
        'airline' => 'British Airways',
        'airline_code' => 'BA',
        'price' => 463.00,
        'currency' => 'GBP',
        'stops' => 0,
        'duration_minutes' => 390,
        'departure_code' => 'LHR',
        'departure_time' => '06:00',
        'departure_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
        'arrival_code' => 'LOS',
        'arrival_time' => '12:30',
        'arrival_date' => Carbon::today()->addDays(12)->format('Y-m-d'),
        'all_offer' => ['booking_token' => 'tok1'],
        'payload' => [
            'price' => '463',
            'itineraries' => [
                [
                    'depAirport' => 'LHR',
                    'arrAirport' => 'LOS',
                    'depTime' => '06:00',
                    'arrTime' => '12:30',
                    'depCity' => 'London',
                    'arrCity' => 'Lagos',
                    'duration' => '6h 30m',
                    'stops' => 'Direct',
                    'depDate' => 'Tue, 16 Sep',
                    'arrDate' => 'Tue, 16 Sep',
                    'airlineCode' => 'BA',
                    'airlineName' => 'British Airways',
                ],
                [
                    'depAirport' => 'LOS',
                    'arrAirport' => 'LHR',
                    'depTime' => '13:00',
                    'arrTime' => '19:30',
                    'depCity' => 'Lagos',
                    'arrCity' => 'London',
                    'duration' => '6h 30m',
                    'stops' => 'Direct',
                    'depDate' => 'Tue, 23 Sep',
                    'arrDate' => 'Tue, 23 Sep',
                    'airlineCode' => 'BA',
                    'airlineName' => 'British Airways',
                ],
            ],
            'bags' => '23kg',
            'cabinBag' => '7kg',
        ],
        'sort_order' => 0,
    ]);

$this->get('/flights/test-lhr-to-lagos-los')
        ->assertOk()
        ->assertSee("Today's Live Prices", false)
        ->assertSee('British Airways')
        ->assertSee('463')
        ->assertSee('Book Now')
        ->assertSee(route('api.offer.verify'))
        ->assertSee('london-lhr-to-lagos-los')
        ->assertDontSee('noindex');

    $response = $this->get('/flights/test-lhr-to-lagos-los');

    $this->assertEquals('LHR', session('last_flight_search.originLocationCode'));
    $this->assertEquals(1, session('last_flight_search.routeModel'));
});

it('falls back to airlines_operating table when no live fares', function () {
    FlightRoute::create([
        'origin_code' => 'LHR',
        'destination_code' => 'LOS',
        'slug' => 'test-lhr-to-lagos-nof',
        'lowest_fare_gbp' => 480.00,
        'is_indexable' => true,
        'search_intent_metadata' => [
            'airlines_operating' => [
                ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 480],
            ],
        ],
    ]);

$this->get('/flights/test-lhr-to-lagos-nof')
        ->assertOk()
        ->assertSee('Airlines Operating This Route')
        ->assertSee('Virgin Atlantic')
        ->assertSee('480')
        ->assertDontSee("Today's Live Prices", false);
});

it('updates live fares and lowest_fare_gbp when command runs', function () {
    $route = FlightRoute::create([
        'origin_code' => 'LHR',
        'destination_code' => 'LOS',
        'slug' => 'test-lhr-to-lagos-cmd',
        'lowest_fare_gbp' => 999.00,
        'is_indexable' => true,
        'search_intent_metadata' => ['airlines_operating' => []],
    ]);

    $flight = [
        'price' => 500,
        'currency' => 'GBP',
        'booking_token' => 'tok_test_123',
        'segments' => [
            [
                [
                    'departure_code' => 'LHR',
                    'arrival_code' => 'LOS',
                    'departure_time' => '06:00',
                    'arrival_time' => '12:30',
                    'departure_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
                    'arrival_date' => Carbon::today()->addDays(5)->format('Y-m-d'),
                    'departure_city' => 'London',
                    'arrival_city' => 'Lagos',
                    'img' => 'BA',
                    'airline' => 'British Airways',
                    'baggage' => '23kg',
                    'cabin_baggage' => '7kg',
                    'total_duration' => '6h 30m',
                    'seg_duration' => '6h 30m',
                ],
            ],
            [
                [
                    'departure_code' => 'LOS',
                    'arrival_code' => 'LHR',
                    'departure_time' => '13:00',
                    'arrival_time' => '19:30',
                    'departure_date' => Carbon::today()->addDays(12)->format('Y-m-d'),
                    'arrival_date' => Carbon::today()->addDays(12)->format('Y-m-d'),
                    'departure_city' => 'Lagos',
                    'arrival_city' => 'London',
                    'img' => 'BA',
                    'airline' => 'British Airways',
                    'baggage' => '23kg',
                    'cabin_baggage' => '7kg',
                    'total_duration' => '6h 30m',
                    'seg_duration' => '6h 30m',
                ],
            ],
        ],
    ];

    $this->mock(SkyLinkApiService::class, function ($mock) use ($flight) {
        $mock->shouldReceive('searchFlights')
             ->once()
             ->andReturn([
                 'success' => true,
                 'data' => ['flights' => [$flight]],
             ]);
    });

    Artisan::call('flight-routes:update-prices');

    $route->refresh();

    $this->assertEquals(500.00, $route->lowest_fare_gbp);
    $this->assertEquals('British Airways', $route->primary_airline);
    $this->assertCount(1, $route->liveFares);
    $this->assertFalse(Cache::has("pseo_flight_route_{$route->slug}"));

    $fare = $route->liveFares()->first();
    $this->assertEquals('BA', $fare->airline_code);
    $this->assertEquals(500.00, $fare->price);
    $this->assertEquals('GBP', $fare->currency);
    $this->assertEquals('LHR', $fare->departure_code);
    $this->assertEquals('LOS', $fare->arrival_code);
    $this->assertEquals(0, $fare->stops);
    $this->assertNotNull($fare->all_offer);
    $this->assertNotNull($fare->payload);
});


