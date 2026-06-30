<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SkyLinkApiService
{
    protected string $baseUrl;
    protected SkyLinkAuthService $authService;

    public function __construct(SkyLinkAuthService $authService)
    {
        $this->authService = $authService;
        $this->baseUrl = rtrim(config('247travels.base_url', 'https://247travels.com/api'), '/');
    }

    protected function getHttpClient()
    {
        $token = $this->authService->getAccessToken();

        return Http::withOptions(['connect_timeout' => 60])
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->timeout(180)
            ->retry(3, 1000, fn($exception) => $exception instanceof \Illuminate\Http\Client\ConnectionException, false);
    }

    protected function parseResponse($response): array
    {
        $body = $response->body();
        $body = preg_replace('/^\xEF\xBB\xBF/', '', $body);
        $decoded = json_decode($body, true);
        if (!$decoded) {
            throw new \Exception('Invalid JSON response from SkyLink: ' . json_last_error_msg());
        }
        return $decoded;
    }

    public function searchFlights(array $validatedData): array
    {
        $payload = [
            'search_mode' => 'external', // local or external
            'flight_type' => $this->mapRouteModel($validatedData['routeModel']),
            'adults' => (int) $validatedData['travelers']['numberOfAdults'],
            'children' => (int) ($validatedData['travelers']['numberOfChildren'] ?? 0),
            'infants' => (int) ($validatedData['travelers']['numberOfInfants'] ?? 0),
            'class' => strtolower($validatedData['flightClass']),
            'currency' => config('currency.default_currency', 'GBP'),
            'nonstop' => $validatedData['directFlightOnly'] ? 1 : 0,
        ];

        if ($payload['flight_type'] === 'multicity') {
            $routes = [];
            for ($i = 1; $i <= 10; $i++) {
                if (isset($validatedData["originLocationCode{$i}"])) {
                    $routes[] = [
                        'from' => $validatedData["originLocationCode{$i}"],
                        'to' => $validatedData["originDestinationCode{$i}"],
                        'date' => Carbon::parse($validatedData["departureDate{$i}"])->format('Y-m-d'),
                    ];
                }
            }
            $payload['routes'] = $routes;
        } else {
            $payload['from'] = $validatedData['originLocationCode'];
            $payload['to'] = $validatedData['originDestinationCode'];
            $payload['flights_departure_date'] = Carbon::parse($validatedData['departureDate'])->format('Y-m-d');

            if ($payload['flight_type'] === 'roundtrip' && isset($validatedData['returnDate'])) {
                $payload['flights_return_date'] = Carbon::parse($validatedData['returnDate'])->format('Y-m-d');
                $payload['flexible_days'] = $validatedData['dateWindow'] ? 1 : 0;
            }
        }

        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/api/flights/search", $payload);
            $decoded = $this->parseResponse($response);

            if (!$response->successful()) {
                Log::error('SkyLink search failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception("Flight search failed: {$response->status()} - " . ($decoded['message'] ?? $response->body()));
            }

            return $decoded;
        } catch (\Exception $e) {
            Log::error('SkyLink search error', ['error' => $e->getMessage()]);
            AdminNotificationService::notify247ApiDown($e->getMessage(), "{$this->baseUrl}/api/flights/search");
            return ['success' => false, 'data' => ['flights' => []]];
        }
    }

    public function verifyPrice(array $offer, ?array $passengers = null): array
    {
        $payload = [
            'booking_token' => $offer['booking_token'],
            'passenger' => $passengers,
            'currency' => $offer['segments'][0][0]['currency'] ?? config('currency.default_currency', 'GBP'),
            'class' => strtolower($offer['segments'][0][0]['class']) ?? 'economy',
        ];
        //dd(json_encode($payload, true));
        
         Log::info('SkyLink verify payload', ['payload' => $payload]);
        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/api/flights/pricing", $payload);
            $decoded = $this->parseResponse($response);

            if (!$response->successful()) {
                Log::error('SkyLink pricing failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception("Price verification failed: {$response->status()} - " . ($decoded['message'] ?? $response->body()));
            }

            return $decoded;
        } catch (\Exception $e) {
            Log::error('SkyLink pricing error', ['error' => $e->getMessage()]);
            AdminNotificationService::notify247ApiDown($e->getMessage(), "{$this->baseUrl}/api/flights/pricing");
            throw $e;
        }
    }

    public function reserveFlight(array $payload): array
    {
        try {
            $logPayload = $payload;
            if (isset($logPayload['travellers']['travelers'])) {
                foreach ($logPayload['travellers']['travelers'] as $k => $t) {
                    unset($logPayload['travellers']['travelers'][$k]['passport_number']);
                    unset($logPayload['travellers']['travelers'][$k]['passport_expiry']);
                    unset($logPayload['travellers']['travelers'][$k]['passport_issue_date']);
                }
                if (isset($logPayload['travellers']['primary_guest'])) {
                    unset($logPayload['travellers']['primary_guest']['passport_number']);
                    unset($logPayload['travellers']['primary_guest']['passport_expiry']);
                    unset($logPayload['travellers']['primary_guest']['passport_issue_date']);
                }
            }
            //Log::info('SkyLink reserve payload', ['payload' => $logPayload]);

            $response = $this->getHttpClient()->post("{$this->baseUrl}/api/flights/reserve", $payload);

            if (!$response->successful()) {
                $rawBody = $response->body();
                Log::error('SkyLink reserve failed', [
                    'status' => $response->status(),
                    'body' => $rawBody,
                ]);

                $decoded = json_decode(preg_replace('/^\xEF\xBB\xBF/', '', $rawBody), true);

                if ($response->status() === 403 && isset($decoded['blocked'])) {
                    Log::error('SkyLink PNR blocked', [
                        'carrier' => $decoded['carrier'] ?? '',
                        'message' => $decoded['message'] ?? '',
                    ]);
                    throw new \Exception($decoded['message'] ?? 'This carrier is currently blocked.');
                }

                throw new \Exception("Flight reservation failed: {$response->status()} - " . ($decoded['message'] ?? substr($rawBody, 0, 2000)));
            }

            $decoded = $this->parseResponse($response);
            return $decoded;
        } catch (\Exception $e) {
            Log::error('SkyLink reserve error', ['error' => $e->getMessage()]);
            AdminNotificationService::notify247ApiDown($e->getMessage(), "{$this->baseUrl}/api/flights/reserve");
            throw $e;
        }
    }

    protected function mapRouteModel(int $routeModel): string
    {
        return match ($routeModel) {
            0 => 'oneway',
            1 => 'roundtrip',
            2 => 'multicity',
            default => 'oneway',
        };
    }
}
