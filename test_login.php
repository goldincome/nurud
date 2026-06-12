<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$baseUrl = $_ENV['247TRAVELS_BASE_URL'] ?? 'https://247travels.cloud';
$username = $_ENV['247TRAVELS_USERNAME'] ?? '';
$password = $_ENV['247TRAVELS_PASSWORD'] ?? '';

echo "Base URL: $baseUrl\n";
echo "Username: $username\n";
echo "Password: " . substr($password, 0, 3) . "***\n\n";

echo "Attempting login...\n";

$start = microtime(true);

try {
    $client = new \GuzzleHttp\Client([
        'connect_timeout' => 120,
        'timeout' => 180,
    ]);

    $response = $client->post("$baseUrl/api/login", [
        'json' => [
            'email' => $username,
            'password' => $password,
        ],
    ]);

    $elapsed = round(microtime(true) - $start, 2);
    $body = json_decode($response->getBody(), true);

    $rawBody = (string)$response->getBody();
    echo "Raw body length: " . strlen($rawBody) . "\n";
    echo "Raw body hex: " . bin2hex(substr($rawBody, 0, 100)) . "\n";
    echo "Raw body:\n" . var_export($rawBody, true) . "\n\n";

    $body = json_decode($rawBody, true);
    $jsonError = json_last_error_msg();
    echo "JSON decode result: " . var_export($body, true) . " (error: $jsonError)\n\n";

    if ($body === null) {
        // try stripping BOM
        $bom = pack('H*', 'EFBBBF');
        $cleaned = preg_replace('/^' . preg_quote($bom, '/') . '/', '', $rawBody);
        $body = json_decode($cleaned, true);
        echo "After BOM strip: " . var_export($body, true) . " (error: " . json_last_error_msg() . ")\n\n";
    }

    if (isset($body['status']) && $body['status'] === 'error') {
        echo "LOGIN FAILED ($elapsed seconds)\n";
        echo "Code: " . ($body['code'] ?? 'N/A') . "\n";
        echo "Message: " . ($body['message'] ?? 'N/A') . "\n";
        if (isset($body['user_id'])) {
            echo "User ID: " . $body['user_id'] . "\n";
        }
        exit(1);
    }

    echo "LOGIN SUCCESS ($elapsed seconds)\n";
    echo "HTTP Status: " . $response->getStatusCode() . "\n";
    echo "Response:\n";
    echo json_encode($body, JSON_PRETTY_PRINT) . "\n";

} catch (\GuzzleHttp\Exception\ConnectException $e) {
    $elapsed = round(microtime(true) - $start, 2);
    echo "FAILED ($elapsed seconds)\n";
    echo "Error type: Connection Error\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Handler Context:\n";
    print_r($e->getHandlerContext());
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $elapsed = round(microtime(true) - $start, 2);
    echo "FAILED ($elapsed seconds)\n";
    echo "Error type: Client Error (HTTP " . $e->getResponse()->getStatusCode() . ")\n";
    echo "Response body: " . $e->getResponse()->getBody() . "\n";
} catch (\GuzzleHttp\Exception\ServerException $e) {
    $elapsed = round(microtime(true) - $start, 2);
    echo "FAILED ($elapsed seconds)\n";
    echo "Error type: Server Error (HTTP " . $e->getResponse()->getStatusCode() . ")\n";
    echo "Response body: " . $e->getResponse()->getBody() . "\n";
} catch (\Exception $e) {
    $elapsed = round(microtime(true) - $start, 2);
    echo "FAILED ($elapsed seconds)\n";
    echo "Error type: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
}
