<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$baseUrl = $_ENV['247TRAVELS_BASE_URL'] ?? 'https://247travels.cloud';
$username = $_ENV['247TRAVELS_USERNAME'] ?? '';
$password = $_ENV['247TRAVELS_PASSWORD'] ?? '';

$client = new \GuzzleHttp\Client(['connect_timeout' => 120, 'timeout' => 180]);

echo "=== LOGIN ===\n";
$res = $client->post("$baseUrl/api/login", ['json' => ['email' => $username, 'password' => $password]]);
$body = preg_replace('/^\xEF\xBB\xBF/', '', (string)$res->getBody());
$loginData = json_decode($body, true);
$token = $loginData['data']['access_token'];
echo "Token obtained: " . substr($token, 0, 30) . "...\n\n";

echo "=== SEARCH FLIGHTS (LOS->ABV, 1 Adult, Economy) ===\n";
try {
    $searchRes = $client->post("$baseUrl/api/flights/search", [
        'headers' => ['Authorization' => "Bearer $token", 'Accept' => 'application/json'],
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
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $resp = $e->getResponse();
    echo "HTTP " . $resp->getStatusCode() . " - " . $resp->getReasonPhrase() . "\n";
    $errorBody = preg_replace('/^\xEF\xBB\xBF/', '', (string)$resp->getBody());
    echo "Response body:\n" . json_encode(json_decode($errorBody, true), JSON_PRETTY_PRINT) . "\n";
    exit(1);
}
$searchBody = preg_replace('/^\xEF\xBB\xBF/', '', (string)$searchRes->getBody());
$searchData = json_decode($searchBody, true);

echo "HTTP Status: " . $searchRes->getStatusCode() . "\n";
echo "Full response structure:\n" . json_encode($searchData, JSON_PRETTY_PRINT) . "\n\n";

if (isset($searchData['success']) && $searchData['success'] === false) {
    echo "ERROR: " . ($searchData['message'] ?? 'Unknown error') . "\n";
    exit(1);
}

$flights = $searchData['data']['flights'] ?? [];
echo "Flights found: " . count($flights) . "\n";
if (count($flights) > 0) {
    echo "\nFirst flight details:\n";
    echo json_encode($flights[0], JSON_PRETTY_PRINT) . "\n";
    echo "\nBooking token: " . substr($flights[0]['booking_token'] ?? 'N/A', 0, 40) . "...\n";

    // Step 3: Pricing
    echo "\n=== PRICING VERIFICATION ===\n";
    $firstFlight = $flights[0];
    try {
        $priceRes = $client->post("$baseUrl/api/flights/pricing", [
            'headers' => ['Authorization' => "Bearer $token", 'Accept' => 'application/json'],
            'json' => [
                'booking_token' => $firstFlight['booking_token'],
            ],
        ]);
        $priceBody = preg_replace('/^\xEF\xBB\xBF/', '', (string)$priceRes->getBody());
        $priceData = json_decode($priceBody, true);
        echo "HTTP Status: " . $priceRes->getStatusCode() . "\n";
        echo "Pricing response:\n" . json_encode($priceData, JSON_PRETTY_PRINT) . "\n";
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $resp = $e->getResponse();
        echo "HTTP " . $resp->getStatusCode() . " - " . $resp->getReasonPhrase() . "\n";
        $errorBody = preg_replace('/^\xEF\xBB\xBF/', '', (string)$resp->getBody());
        echo "Response body:\n" . json_encode(json_decode($errorBody, true), JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "Full response:\n" . json_encode($searchData, JSON_PRETTY_PRINT) . "\n";
}

// Step 4: Multi-city search
echo "\n\n=== MULTI-CITY SEARCH (LOS->ABV->LOS) ===\n";
try {
    $mcRes = $client->post("$baseUrl/api/flights/search", [
        'headers' => ['Authorization' => "Bearer $token", 'Accept' => 'application/json'],
        'json' => [
            'search_mode' => 'external',
            'flight_type' => 'multicity',
            'routes' => [
                ['from' => 'LOS', 'to' => 'ABV', 'date' => '2026-06-10'],
                ['from' => 'ABV', 'to' => 'LOS', 'date' => '2026-06-14'],
            ],
            'adults' => 1,
            'children' => 0,
            'infants' => 0,
            'class' => 'economy',
            'currency' => 'NGN',
        ],
    ]);
    $mcBody = preg_replace('/^\xEF\xBB\xBF/', '', (string)$mcRes->getBody());
    $mcData = json_decode($mcBody, true);
    echo "HTTP Status: " . $mcRes->getStatusCode() . "\n";
    $mcFlights = $mcData['data']['flights'] ?? [];
    echo "Multi-city flights found: " . count($mcFlights) . "\n";
    if (count($mcFlights) > 0) {
        echo "First flight:\n" . json_encode($mcFlights[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "Full response:\n" . json_encode($mcData, JSON_PRETTY_PRINT) . "\n";
    }
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $resp = $e->getResponse();
    echo "HTTP " . $resp->getStatusCode() . " - " . $resp->getReasonPhrase() . "\n";
    $errorBody = preg_replace('/^\xEF\xBB\xBF/', '', (string)$resp->getBody());
    echo "Response body:\n" . json_encode(json_decode($errorBody, true), JSON_PRETTY_PRINT) . "\n";
}
