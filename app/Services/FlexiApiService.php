<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FlexiApiService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $environment;

    public function __construct()
    {
        $this->environment = config('247travels.environment');
        $this->baseUrl = config('247travels.live_url');
        if($this->environment === 'test'){
            $this->baseUrl = config('247travels.test_url');
        }
        $this->apiKey = config('247travels.secret_key');
    }

    protected function getHttpClient()
    {
        return Http::withHeaders([
            'Authorization-Bearer-Token' => $this->apiKey, // Custom Header Key
        ])
        ->withToken($this->apiKey)
        ->acceptJson()
        ->timeout(120)
        ->retry(3, 100, fn ($exception) => $exception instanceof \Illuminate\Http\Client\ConnectionException);
    }
    public function searchFlights(array $validatedData): array
    {
       // 1. Map the Flat Form Data to the Nested API JSON Structure
        $payload = [
            'directFlightOnly' => $validatedData['directFlightOnly'],
            'flightClass' => $validatedData['flightClass'],
            'dateWindow' => $validatedData['dateWindow'],
            'travelers' => [
                'numberOfAdults' => (int) $validatedData['travelers']['numberOfAdults'],
                'numberOfChildren' => (int) ($validatedData['travelers']['numberOfChildren'] ?? 0),
                'numberOfInfants' => (int) ($validatedData['travelers']['numberOfInfants'] ?? 0),
            ],
            'routeModel' => (int) $validatedData['routeModel'],
        ];

        if($payload['routeModel'] === 0){
            $payload['oneWay'] = [
                'originLocationCode' => $validatedData['originLocationCode'],
                'originDestinationCode' => $validatedData['originDestinationCode'],
                'departureDate' => Carbon::parse($validatedData['departureDate'])->format('Y-m-d'),
            ];
        }
        // Handle Round Trip / One Way Mapping
        if ($payload['routeModel'] === 1) {
            $payload['roundTrip'] = [
                'originLocationCode' => $validatedData['originLocationCode'],
                'originDestinationCode' => $validatedData['originDestinationCode'],
                'departureDate' => Carbon::parse($validatedData['departureDate'])->format('Y-m-d'),
                'returnDate' => isset($validatedData['returnDate']) 
                    ? Carbon::parse($validatedData['returnDate'])->format('Y-m-d') 
                    : null,
            ];
        }

        // 4. Handle Multi-City (2) - NEW IMPLEMENTATION
        if ($payload['routeModel'] === 2) {
            $segments = [];
            $out = [];
            // Loop through the potential 10 segments defined in your Request validation
            for ($i = 1; $i <= 10; $i++) {
                // Check if the "originLocationCodeX" exists for this number
                if (isset($validatedData["originLocationCode{$i}"])) {
                    $segments = [
                        'originLocationCode'.$i => $validatedData["originLocationCode{$i}"],
                        'originDestinationCode'.$i => $validatedData["originDestinationCode{$i}"],
                        'departureDate'.$i => Carbon::parse($validatedData["departureDate{$i}"])->format('Y-m-d'),
                    ];
                    $out = array_merge($segments, $out);
                }
            }
            
            // Assign the list of segments to the API payload
            // Note: I am assuming your API expects a key named 'multiCity' containing an array of flights
            $payload['multiCity'] = $out ; //$segments;
        }

        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/flight-search", $payload);
            
            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Flexi API search failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'params' => $payload
            ]);

            throw new \Exception("Flight search failed: {$response->status()} - {$response->body()}");
        } catch (\Exception $e) {
            Log::error('Flexi API search error', [
                'error' => $e->getMessage(),
                'params' => $payload
            ]);
            // Fallback to mock data in case of API failure
            return [];
        }
    }

    protected function getMockFlights(array $params): array
    {
        $flights = [];
        $airlines = ['Air Peace', 'Arik Air', 'Dana Air', 'Ibom Air', 'Green Africa Airways'];
        $aircraft = ['Boeing 737-800', 'Airbus A320', 'Embraer E195', 'Boeing 777', 'Airbus A350'];

        // Generate 5-10 mock flights
        $numFlights = rand(5, 10);

        for ($i = 0; $i < $numFlights; $i++) {
            $departureOffset = rand(-30, 30); // Minutes from requested time
            $departureTime = date('H:i', strtotime("+{$departureOffset} minutes", strtotime($params['date'] . ' 08:00:00')));
            $duration = rand(60, 300); // 1-5 hours in minutes
            $arrivalTime = date('H:i', strtotime("{$departureTime} +{$duration} minutes"));
            $basePrice = rand(15000, 80000);
            $stops = rand(0, 2);

            $flights[] = [
                'offer_id' => 'MOCK_' . strtoupper(uniqid()),
                'airline' => $airlines[array_rand($airlines)],
                'flight_number' => chr(65 + rand(0, 5)) . rand(100, 999),
                'departure_time' => $departureTime,
                'arrival_time' => $arrivalTime,
                'duration' => sprintf('%dh %dm', floor($duration / 60), $duration % 60),
                'stops' => $stops,
                'aircraft' => $aircraft[array_rand($aircraft)],
                'class' => 'Economy',
                'price' => $basePrice + ($stops * 2000), // Add extra for multi-stop
                'currency' => $params['currency'] ?? 'NGN'
            ];
        }

        // Sort by price ascending
        usort($flights, fn($a, $b) => $a['price'] <=> $b['price']);

        return [
            'data' => $flights,
            'meta' => [
                'total' => count($flights),
                'currency' => $params['currency'] ?? 'NGN'
            ]
        ];
    }

    public function verifyPrice(array $offer): array
    {

        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/offer/verify", $offer);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Flexi API verify price failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'offer' => $offer
            ]);

            throw new \Exception("Price verification failed: {$response->status()} - {$response->body()}");
        } catch (\Exception $e) {
            Log::error('Flexi API verify price error', [
                'error' => $e->getMessage(),
                'offer' => $offer
            ]);
            throw $e;
        }
    }

    public function reserveFlight(array $offer): array
    {
        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/offer/reserve", $offer);     

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Flexi API reserve failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("Flight reservation failed: {$response->status()} - {$response->body()}");
        } catch (\Exception $e) {
            Log::error('Flexi API reserve error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function issueTicket(string $pnr): array
    {
        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/offer/book", [
                'pnr' => $pnr
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Flexi API book failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'pnr' => $pnr
            ]);

            throw new \Exception("Ticket issuance failed: {$response->status()} - {$response->body()}");
        } catch (\Exception $e) {
            Log::error('Flexi API book error', [
                'error' => $e->getMessage(),
                'pnr' => $pnr
            ]);
            throw $e;
        }
    }
}
