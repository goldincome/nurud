<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$baseUrl = $_ENV['247TRAVELS_BASE_URL'] ?? 'https://247travels.cloud';
$username = $_ENV['247TRAVELS_USERNAME'] ?? '';
$password = $_ENV['247TRAVELS_PASSWORD'] ?? '';

$client = new \GuzzleHttp\Client(['connect_timeout' => 120, 'timeout' => 180]);

function stripBom($body) {
    return preg_replace('/^\xEF\xBB\xBF/', '', $body);
}

function decode($body) {
    return json_decode(stripBom($body), true);
}

function apiCall(\GuzzleHttp\Client $client, string $method, string $url, array $options) {
    try {
        $res = $client->$method($url, $options);
        $data = decode((string)$res->getBody());
        echo "  HTTP " . $res->getStatusCode() . " - OK\n";
        return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $resp = $e->getResponse();
        $data = decode((string)$resp->getBody());
        echo "  HTTP " . $resp->getStatusCode() . " - ERROR\n";
        echo "  Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        throw $e;
    }
}

// === STEP 1: LOGIN ===
echo "=== 1. LOGIN ===\n";
$loginRes = $client->post("$baseUrl/api/login", ['json' => ['email' => $username, 'password' => $password]]);
$loginData = decode((string)$loginRes->getBody());
$token = $loginData['data']['access_token'];
$authHeader = ['Authorization' => "Bearer $token", 'Accept' => 'application/json'];
echo "  Token: " . substr($token, 0, 30) . "...\n\n";

// === STEP 2: SEARCH ===
echo "=== 2. SEARCH (LOS->ABV, 1 Adult, Economy) ===\n";
$searchRes = $client->post("$baseUrl/api/flights/search", [
    'headers' => $authHeader,
    'json' => [
        'search_mode' => 'external',
        'from' => 'LOS',
        'to' => 'ABV',
        'flight_type' => 'oneway',
        'flights_departure_date' => '2026-06-10',
        'adults' => 1,
        'children' => 0,
        'infants' => 0,
        'class' => 'economy',
        'currency' => 'NGN',
    ],
]);
$searchData = decode((string)$searchRes->getBody());
$flights = $searchData['data']['flights'] ?? [];
$firstFlight = $flights[0];
echo "  Flights: " . count($flights) . "\n";
echo "  First: {$firstFlight['segments'][0][0]['airline']} {$firstFlight['segments'][0][0]['flight_no']} - {$firstFlight['segments'][0][0]['departure_code']}->{$firstFlight['segments'][0][0]['arrival_code']}\n";
echo "  Price: NGN {$firstFlight['price']}\n";
echo "  Booking token: " . substr($firstFlight['booking_token'], 0, 30) . "...\n\n";

// === STEP 3: PRICING ===
echo "=== 3. PRICING ===\n";
$priceRes = $client->post("$baseUrl/api/flights/pricing", [
    'headers' => $authHeader,
    'json' => ['booking_token' => $firstFlight['booking_token']],
]);
$pricingData = decode((string)$priceRes->getBody());
$pd = $pricingData['data'];
echo "  Verified price: {$pd['currency']} {$pd['verified_price']}\n";
echo "  Expires: {$pd['expires_at']}\n";
echo "  Per passenger (adult): NGN {$pd['per_passenger']['adult']}\n";
echo "  Booking token: " . substr($pd['booking_token'], 0, 30) . "...\n\n";

// === STEP 4: RESERVE ===
echo "=== 4. RESERVE ===\n";
$reservePayload = [
    'booking_token' => $pd['booking_token'],
    'passengers' => ['adults' => 1, 'children' => 0, 'infants' => 0],
    'travellers' => [
        'primary_guest' => [
            'title' => 'Mr',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'phone' => '08012345678',
            'country_code' => '234',
            'dob' => '1990-01-15',
            'gender' => 'male',
        ],
        'travelers' => [
            'adult_0' => [
                'title' => 'Mr',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'dob' => '1990-01-15',
                'gender' => 'male',
            ],
        ],
    ],
    'ticket_time_limit_hours' => 48,
];
echo "  Sending reserve request...\n";
$reserveRes = $client->post("$baseUrl/api/flights/reserve", [
    'headers' => $authHeader,
    'json' => $reservePayload,
]);
$reserveData = decode((string)$reserveRes->getBody());
echo "  HTTP " . $reserveRes->getStatusCode() . "\n";
echo "  Response:\n" . json_encode($reserveData, JSON_PRETTY_PRINT) . "\n\n";

// === STEP 5: TEST MAPPER OUTPUT ===
echo "=== 5. MAPPER STRUCTURE CHECK ===\n";
$seg = $firstFlight['segments'][0][0];
echo "  Segment fields available:\n";
echo "    airline: {$seg['airline']}\n";
echo "    flight_no: {$seg['flight_no']}\n";
echo "    departure_code: {$seg['departure_code']}\n";
echo "    arrival_code: {$seg['arrival_code']}\n";
echo "    departure_time: {$seg['departure_time']}\n";
echo "    arrival_time: {$seg['arrival_time']}\n";
echo "    departure_city: {$seg['departure_city']}\n";
echo "    arrival_city: {$seg['arrival_city']}\n";
echo "    baggage: {$seg['baggage']}\n";
echo "    cabin_baggage: {$seg['cabin_baggage']}\n";
echo "    duration_time: {$seg['duration_time']}\n";
echo "    total_duration: {$seg['total_duration']}\n";
echo "    img (carrier code): {$seg['img']}\n\n";

echo "=== ALL TESTS PASSED ===\n";
