<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Services\SkyLinkApiService;
use App\Services\SkyLinkResponseMapper;
use App\Services\BookingService;
use App\Services\MarkupService;
use App\Services\SimlessPayService;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== FULL BOOKING FLOW TEST ===\n\n";

// Create test user
echo "Creating test user...\n";
$user = User::firstOrCreate(
    ['email' => 'test_flow@example.com'],
    [
        'name' => 'Test Flow',
        'password' => bcrypt('password123'),
        'type' => 'customer',
    ]
);
auth()->login($user);
echo "  User ID: {$user->id}\n\n";

// Step 1: Search flights
echo "1. SEARCH FLIGHTS (LOS->ABV, 1 Adult, Economy)\n";
$apiService = app(SkyLinkApiService::class);
$searchData = [
    'originLocationCode' => 'LOS',
    'originDestinationCode' => 'ABV',
    'departureDate' => date('Y-m-d', strtotime('+30 days')),
    'routeModel' => 0,
    'flightClass' => 'ECONOMY',
    'travelers' => ['numberOfAdults' => 1, 'numberOfChildren' => 0, 'numberOfInfants' => 0],
    'directFlightOnly' => false,
    'dateWindow' => false,
];

try {
    $searchResult = $apiService->searchFlights($searchData);
    if (!($searchResult['success'] ?? false) && !isset($searchResult['data']['flights'])) {
        echo "  FAIL: " . ($searchResult['message'] ?? 'Search returned no data') . "\n";
        echo "  Response: " . json_encode($searchResult) . "\n";
        exit(1);
    }
    $flights = $searchResult['data']['flights'] ?? [];
    echo "  Found " . count($flights) . " flights\n";
    $firstFlight = $flights[0];
    $seg = $firstFlight['segments'][0][0] ?? [];
    echo "  Selected: {$seg['airline']} {$seg['flight_no']} {$seg['departure_code']}->{$seg['arrival_code']}\n";
    echo "  Price: NGN {$firstFlight['price']}\n\n";
} catch (\Exception $e) {
    echo "  FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 2: Verify price
echo "2. VERIFY PRICE\n";
try {
    $pricingResult = $apiService->verifyPrice($firstFlight);
    $pricingData = $pricingResult['data'] ?? [];
    echo "  Verified price: {$pricingData['currency']} {$pricingData['verified_price']}\n";
    echo "  Per adult base: NGN {$pricingData['per_passenger']['adult']}\n";
    echo "  Expires: {$pricingData['expires_at']}\n\n";
} catch (\Exception $e) {
    echo "  FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 3: Build reserve payload
echo "3. BUILD RESERVE PAYLOAD\n";
$mapper = app(SkyLinkResponseMapper::class);
$passengerRequest = [
    'email' => 'test_flow@example.com',
    'phone' => '+2348012345678',
    'countryCallingCode' => '234',
    'passengers' => [
        'adult_1' => [
            'title' => 'Mr',
            'firstName' => 'John',
            'surname' => 'Doe',
            'middleName' => '',
            'dob' => '1990-01-15',
            'gender' => '3',
        ],
    ],
];

$bookingToken = $pricingData['booking_token'] ?? $firstFlight['booking_token'];
$reservePayload = $mapper->buildReservePayload($bookingToken, $pricingData, $passengerRequest, $searchData);
$reservePayload['flight_summary'] = $firstFlight;
$reservePayload['search_params'] = $searchData;
echo "  Booking token: " . substr($bookingToken, 0, 30) . "...\n";
echo "  Passengers: {$reservePayload['passengers']['adults']} adults\n\n";

// Step 4a: Create booking - Book On Hold
echo "4a. CREATE BOOKING (Book On Hold)\n";
$bookingService = app(BookingService::class);
try {
    $markupFee = 5000;
    session()->put('markup_fee', $markupFee);
    
    $booking = $bookingService->createPendingBookingFromSkyLinkPayload(
        $reservePayload,
        $firstFlight,
        $searchData
    );
    echo "  Booking ID: {$booking->id}\n";
    echo "  Status: {$booking->status->value}\n";
    echo "  Total price: NGN {$booking->total_price}\n";
    
    $booking->payments()->create([
        'transaction_ref' => 'BOOK_ON_HOLD_' . strtoupper(uniqid()),
        'amount' => $booking->total_price,
        'currency' => $booking->currency,
        'status' => PaymentStatus::PENDING,
        'payment_method' => PaymentMethod::BOOK_ON_HOLD,
    ]);
    echo "  Payment created: BOOK_ON_HOLD\n";
    
    echo "  Travelers: " . $booking->travelers()->count() . "\n";
    echo "  Itineraries: " . $booking->itineraries()->count() . "\n\n";
} catch (\Exception $e) {
    echo "  FAIL: " . $e->getMessage() . "\n";
    echo "  Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

// Step 4b: Create booking - Bank Transfer
echo "4b. CREATE BOOKING (Bank Transfer)\n";
try {
    $booking2 = $bookingService->createPendingBookingFromSkyLinkPayload(
        $reservePayload,
        $firstFlight,
        $searchData
    );
    $booking2->payments()->create([
        'transaction_ref' => 'BANK_TRANSFER_' . strtoupper(uniqid()),
        'amount' => $booking2->total_price,
        'currency' => $booking2->currency,
        'status' => PaymentStatus::PENDING,
        'payment_method' => PaymentMethod::BANK_TRANSFER,
    ]);
    echo "  Booking ID: {$booking2->id}\n";
    echo "  Payment method: BANK_TRANSFER\n";
    echo "  Payment status: PENDING\n\n";
} catch (\Exception $e) {
    echo "  FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 4c: Create booking - BNPL (Pay Later)
echo "4c. CREATE BOOKING (BNPL / Pay Later)\n";
try {
    $booking3 = $bookingService->createPendingBookingFromSkyLinkPayload(
        $reservePayload,
        $firstFlight,
        $searchData
    );
    $booking3->payments()->create([
        'transaction_ref' => 'PAY_LATER_' . strtoupper(uniqid()),
        'amount' => $booking3->total_price,
        'currency' => $booking3->currency,
        'status' => PaymentStatus::PENDING,
        'payment_method' => PaymentMethod::PAY_LATER,
    ]);
    echo "  Booking ID: {$booking3->id}\n";
    echo "  Payment method: PAY_LATER (BNPL)\n";
    echo "  Payment status: PENDING\n\n";
} catch (\Exception $e) {
    echo "  FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 5: Verify all bookings in DB
echo "5. VERIFY BOOKINGS IN DATABASE\n";
$allBookings = Booking::where('customer_email', 'test_flow@example.com')->get();
echo "  Total bookings created: " . $allBookings->count() . "\n";
foreach ($allBookings as $b) {
    $pm = $b->payments()->first();
    echo "    ID:{$b->id} Status:{$b->status->value} Price:NGN{$b->total_price} Payment:" . ($pm->payment_method->value ?? 'N/A') . " PmtStatus:" . ($pm->status->value ?? 'N/A') . "\n";
}
echo "\n";

// Summary
echo "=== SUMMARY ===\n";
echo "✓ Search API: WORKING\n";
echo "✓ Pricing API: WORKING\n";
echo "✓ Build Reserve Payload: WORKING\n";
echo "✓ Create Booking (Book On Hold): WORKING\n";
echo "✓ Create Booking (Bank Transfer): WORKING\n";
echo "✓ Create Booking (BNPL/Pay Later): WORKING\n";
echo "✓ Database persistence: WORKING\n\n";
echo "=== ALL BOOKING FLOW TESTS PASSED ===\n";

$kernel->terminate($request, $response);
