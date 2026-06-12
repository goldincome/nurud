# SkyLink API Integration — Comprehensive Implementation Guide

**Project:** Nurud Travels (Laravel)  
**Target API:** SkyLink External API v1.0 (247Travels)  
**Current API:** FlexiAPI (Legacy)  
**Environment Variables:** `247TRAVELS_USERNAME`, `247TRAVELS_PASSWORD`, `247TRAVELS_BASE_URL`

---

## Table of Contents
1. [Executive Summary](#1-executive-summary)
2. [Critical Architectural Differences](#2-critical-architectural-differences)
3. [Recommended Architecture](#3-recommended-architecture)
4. [Phase 1 — Configuration & Auth Layer](#4-phase-1--configuration--auth-layer)
5. [Phase 2 — Core API Service](#5-phase-2--core-api-service)
6. [Phase 3 — Search & Results Flow](#6-phase-3--search--results-flow)
7. [Phase 4 — Booking & Pricing Flow](#7-phase-4--booking--pricing-flow)
8. [Phase 5 — Payment Integration](#8-phase-5--payment-integration)
9. [Phase 6 — Admin Panel Updates](#9-phase-6--admin-panel-updates)
10. [Phase 7 — Blade View Updates](#10-phase-7--blade-view-updates)
11. [Phase 8 — Database & Model Changes](#11-phase-8--database--model-changes)
12. [Phase 9 — Error Handling & Reliability](#12-phase-9--error-handling--reliability)
13. [Phase 10 — Testing & Rollback](#13-phase-10--testing--rollback)

---

## 1. Executive Summary

This guide details the migration from the legacy **FlexiAPI** to the new **SkyLink API**. The migration is **not a direct endpoint swap** — SkyLink introduces fundamental workflow changes:

| Aspect | FlexiAPI (Current) | SkyLink (New) |
|--------|-------------------|---------------|
| **Authentication** | Static API Key header (`Authorization-Bearer-Token`) | JWT Bearer via `POST /api/login` (15-min expiry) |
| **Search** | `POST /flight-search` | `POST /api/flights/search` |
| **Price Verify** | `POST /offer/verify` | `POST /api/flights/pricing` |
| **Reserve** | `POST /offer/reserve` (pending PNR) | `POST /api/flights/reserve` (**instant live PNR**) |
| **Ticket Issue** | `POST /offer/book` (separate step) | **Not required** — PNR created at reserve |
| **Payment Timing** | Reserve first, pay later, then issue ticket | **MUST pay BEFORE reserve** (Terms violation otherwise) |

### Core Challenge & Solution
SkyLink's `/api/flights/reserve` generates a **live airline PNR immediately** and expects payment to already be collected. This conflicts with the current BNPL/Bank Transfer/Book-on-Hold flows.

**Solution: Deferred Reservation Pattern**
- **Stripe (instant payment):** Collect payment first → Call SkyLink reserve in webhook → PNR assigned immediately.
- **BNPL / Bank Transfer / Book-on-Hold:** Create a **local-only** pending booking. Do **NOT** call SkyLink reserve. When admin later marks as CONFIRMED or payment is verified, **then** call SkyLink reserve.
- This maintains full backward compatibility for admin workflows and user payment options while staying compliant with SkyLink Terms of Service.

---

## 2. Critical Architectural Differences

### 2.1 JWT Authentication Flow
```
POST /api/login
Body: { "email": "...", "password": "..." }
Response: { "access_token": "...", "refresh_token": "...", "expires_in": 900 }
```
- `access_token` expires in **15 minutes**.
- `refresh_token` valid for **15 days**.
- All subsequent requests require: `Authorization: Bearer {access_token}`.

### 2.2 Search Endpoint Mapping
**FlexiAPI Payload:**
```json
{
  "directFlightOnly": false,
  "flightClass": "ECONOMY",
  "dateWindow": false,
  "travelers": { "numberOfAdults": 1, "numberOfChildren": 0, "numberOfInfants": 0 },
  "routeModel": 1,
  "roundTrip": { "originLocationCode": "LOS", "originDestinationCode": "DXB", "departureDate": "2026-06-26", "returnDate": "2026-07-10" }
}
```

**SkyLink Payload:**
```json
{
  "search_mode": "external",
  "flight_type": "roundtrip",
  "from": "LOS",
  "to": "DXB",
  "flights_departure_date": "2026-06-26",
  "flights_return_date": "2026-07-10",
  "adults": 1,
  "children": 0,
  "infants": 0,
  "class": "economy",
  "currency": "NGN"
}
```

**Key mappings:**
- `routeModel` 0 → `flight_type: "oneway"`
- `routeModel` 1 → `flight_type: "roundtrip"`
- `routeModel` 2 → `flight_type: "multicity"` + `routes: [{from, to, date}, ...]`
- `travelers.numberOfAdults` → `adults`
- `flightClass` → `class` (lowercase)
- `search_mode` should be `"external"` for live inventory.

### 2.3 Pricing Endpoint Mapping
**FlexiAPI:** `POST /offer/verify` — send full offer array.  
**SkyLink:** `POST /api/flights/pricing` — send `booking_token` + passenger counts.

SkyLink pricing returns:
```json
{
  "booking_token": "btk_...",
  "verified_price": 759354,
  "price_changed": false,
  "expires_at": "2026-05-17 14:30:00",
  "currency": "NGN",
  "passengers": { "adults": 1, "children": 0, "infants": 0, "total": 1 }
}
```

### 2.4 Reserve Endpoint Mapping
**FlexiAPI:** `POST /offer/reserve` returns reservation details without PNR.  
**SkyLink:** `POST /api/flights/reserve` returns confirmed PNR immediately.

SkyLink reserve payload:
```json
{
  "booking_token": "btk_...",
  "passengers": { "adults": 1, "children": 0, "infants": 0 },
  "travellers": {
    "primary_guest": {
      "title": "Mr",
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "phone": "08012345678",
      "country_code": "234",
      "dob": "1990-01-01",
      "gender": "male",
      "passport_number": "A12345678",
      "passport_expiry": "2030-01-01",
      "nationality": "NG"
    },
    "travelers": {
      "adult_0": { ...same structure... }
    }
  },
  "ticket_time_limit_hours": 48
}
```

**Key differences:**
- `travellers.primary_guest` is always required (lead passenger & contact).
- Additional passengers keyed as `adult_0`, `adult_1`, `child_0`, `infant_0`.
- `adult_0` mirrors `primary_guest` and must not be omitted.
- Gender: `"male"` or `"female"` (not `1`/`2`/`3`).
- `gender` values from current form: `1 = unspecified, 2 = female, 3 = male` → map accordingly.

### 2.5 Response Structure Differences
**FlexiAPI search** returns deeply nested `offerInfos[]` with `itineraries[]`, `segments[]`, `travelerPricings[]`, `priceBreakdown`, etc.  
**SkyLink search** returns a flat `flights[]` array:
```json
{
  "flight_no": "EK784",
  "airline": "EK",
  "airline_name": "Emirates",
  "departure_code": "LOS",
  "arrival_code": "DXB",
  "departure_time": "17:40",
  "arrival_time": "04:25",
  "duration_time": "6h 45m",
  "class": "Economy",
  "baggage": "30kg",
  "price": 759354,
  "currency": "NGN",
  "booking_token": "btk_..."
}
```

This means **search-result.blade.php**, **booking.blade.php**, and **booking/checkout.blade.php** must be updated to render from the simpler SkyLink structure (or from a mapped transformer that preserves the old structure).

---

## 3. Recommended Architecture

### Service Layer Stack
```
┌─────────────────────────────────────┐
│       Controllers / Blade           │
├─────────────────────────────────────┤
│   SkyLinkResponseMapper (Adapter)   │ ← Bridges old view expectations with new API
├─────────────────────────────────────┤
│      SkyLinkApiService              │ ← searchFlights(), verifyPrice(), reserveFlight()
├─────────────────────────────────────┤
│      SkyLinkAuthService             │ ← login(), getToken(), refreshToken()
├─────────────────────────────────────┤
│   Illuminate\Support\Facades\Http   │ ← HTTP client with JWT auto-injection
├─────────────────────────────────────┤
│         Laravel Cache               │ ← Token storage, search cache, booking cache
└─────────────────────────────────────┘
```

### Booking Flow Architecture (Deferred Reservation)
```
┌──────────┐    ┌──────────┐    ┌──────────────┐    ┌─────────────────┐
│  Search  │───→│  Pricing │───→│ Passenger Form│───→│  Checkout Page  │
└──────────┘    └──────────┘    └──────────────┘    └─────────────────┘
                                                           │
            ┌──────────────────────────────────────────────┼────────────────┐
            │                                              │                │
            ▼                                              ▼                ▼
    ┌──────────────┐                            ┌─────────────────┐   ┌─────────────┐
    │ Pay with Card│                            │ Bank Transfer   │   │ Book on Hold│
    │  (Stripe)    │                            │  / BNPL         │   │             │
    └──────┬───────┘                            └────────┬────────┘   └──────┬──────┘
           │                                             │                   │
           ▼                                             ▼                   ▼
    ┌──────────────┐                            ┌─────────────────┐   ┌─────────────┐
    │ LOCAL booking│                            │ LOCAL booking   │   │ LOCAL booking│
    │  (pending)   │                            │  (pending)      │   │  (pending)   │
    │ NO API CALL  │                            │  NO API CALL    │   │  NO API CALL │
    └──────┬───────┘                            └─────────────────┘   └─────────────┘
           │
           ▼
    ┌──────────────┐
    │ Stripe pays  │
    │ Webhook fired│
    └──────┬───────┘
           │
           ▼
    ┌────────────────────────────┐
    │ Call SkyLink /api/flights/ │
    │ reserve() with cached      │
    │ passenger + booking_token  │
    └──────┬─────────────────────┘
           │
           ▼
    ┌──────────────┐
    │ Update local │
    │ booking with │
    │ PNR + CONFIRM│
    └──────────────┘
```

**For BNPL/Bank Transfer/Book-on-Hold:** The admin "Confirm Booking" button later triggers the SkyLink reserve call.

---

## 4. Phase 1 — Configuration & Auth Layer

### 4.1 Update `config/247travels.php`
Replace the old FlexiAPI config with SkyLink credentials:

```php
<?php
return [
    'username' => env('247TRAVELS_USERNAME'),
    'password' => env('247TRAVELS_PASSWORD'),
    'base_url' => env('247TRAVELS_BASE_URL', 'https://247travels.com/api'),
    
    // Token cache keys
    'token_cache_key' => 'skylink_access_token',
    'refresh_token_cache_key' => 'skylink_refresh_token',
    'token_expiry_cache_key' => 'skylink_token_expires_at',
];
```

### 4.2 Create `app/Services/SkyLinkAuthService.php`
```php
<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SkyLinkAuthService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('247travels.base_url'), '/');
        $this->username = config('247travels.username');
        $this->password = config('247travels.password');
    }

    public function getAccessToken(): string
    {
        $token = Cache::get(config('247travels.token_cache_key'));
        $expiresAt = Cache::get(config('247travels.token_expiry_cache_key'));

        // If token expires in < 2 minutes, refresh proactively
        if ($token && $expiresAt && now()->lt($expiresAt->subMinutes(2))) {
            return $token;
        }

        return $this->login();
    }

    protected function login(): string
    {
        try {
            $response = Http::timeout(30)
                ->post("{$this->baseUrl}/api/login", [
                    'email' => $this->username,
                    'password' => $this->password,
                ]);

            if (!$response->successful()) {
                Log::error('SkyLink login failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('SkyLink authentication failed: ' . $response->body());
            }

            $data = $response->json('data');
            $accessToken = $data['access_token'];
            $refreshToken = $data['refresh_token'];
            $expiresIn = $data['expires_in'] ?? 900;

            $expiresAt = now()->addSeconds($expiresIn);

            Cache::put(config('247travels.token_cache_key'), $accessToken, $expiresAt);
            Cache::put(config('247travels.refresh_token_cache_key'), $refreshToken, now()->addDays(14));
            Cache::put(config('247travels.token_expiry_cache_key'), $expiresAt, $expiresAt);

            return $accessToken;
        } catch (\Exception $e) {
            Log::error('SkyLink login exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function clearTokens(): void
    {
        Cache::forget(config('247travels.token_cache_key'));
        Cache::forget(config('247travels.refresh_token_cache_key'));
        Cache::forget(config('247travels.token_expiry_cache_key'));
    }
}
```

---

## 5. Phase 2 — Core API Service

### 5.1 Create `app/Services/SkyLinkApiService.php`
This replaces `FlexiApiService` entirely.

```php
<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\AdminNotificationService;
use App\Services\SkyLinkAuthService;

class SkyLinkApiService
{
    protected string $baseUrl;
    protected SkyLinkAuthService $authService;

    public function __construct(SkyLinkAuthService $authService)
    {
        $this->authService = $authService;
        $this->baseUrl = rtrim(config('247travels.base_url'), '/');
    }

    protected function getHttpClient()
    {
        $token = $this->authService->getAccessToken();

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
        ->timeout(120)
        ->retry(3, 100, function ($exception) {
            return $exception instanceof \Illuminate\Http\Client\ConnectionException;
        });
    }

    /**
     * Step 1: Search flights
     */
    public function searchFlights(array $validatedData): array
    {
        $payload = [
            'search_mode' => 'external',
            'flight_type' => $this->mapRouteModel($validatedData['routeModel']),
            'adults' => (int) $validatedData['travelers']['numberOfAdults'],
            'children' => (int) ($validatedData['travelers']['numberOfChildren'] ?? 0),
            'infants' => (int) ($validatedData['travelers']['numberOfInfants'] ?? 0),
            'class' => strtolower($validatedData['flightClass']),
            'currency' => 'NGN',
        ];

        if ($validatedData['routeModel'] === 2) {
            // Multi-city
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

            if ($validatedData['routeModel'] === 1) {
                $payload['flights_return_date'] = isset($validatedData['returnDate'])
                    ? Carbon::parse($validatedData['returnDate'])->format('Y-m-d')
                    : null;
            }
        }

        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/api/flights/search", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SkyLink search failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Flight search failed: {$response->status()}");
        } catch (\Exception $e) {
            Log::error('SkyLink search error', ['error' => $e->getMessage()]);
            AdminNotificationService::notify247ApiDown($e->getMessage(), "{$this->baseUrl}/api/flights/search");
            return ['success' => false, 'data' => ['flights' => []]];
        }
    }

    /**
     * Step 2: Verify / Reprice flight
     */
    public function verifyPrice(array $offer): array
    {
        $payload = [
            'booking_token' => $offer['booking_token'] ?? $offer['id'] ?? null,
            'passengers' => [
                'adults' => $offer['adults'] ?? 1,
                'children' => $offer['children'] ?? 0,
                'infants' => $offer['infants'] ?? 0,
            ],
            'currency' => $offer['currency'] ?? 'NGN',
            'class' => strtolower($offer['class'] ?? 'economy'),
        ];

        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/api/flights/pricing", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SkyLink pricing failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Price verification failed: {$response->status()}");
        } catch (\Exception $e) {
            Log::error('SkyLink pricing error', ['error' => $e->getMessage()]);
            AdminNotificationService::notify247ApiDown($e->getMessage(), "{$this->baseUrl}/api/flights/pricing");
            throw $e;
        }
    }

    /**
     * Step 3: Reserve / Generate PNR
     * ⚠️ ONLY call this AFTER payment is collected.
     */
    public function reserveFlight(array $payload): array
    {
        try {
            $response = $this->getHttpClient()->post("{$this->baseUrl}/api/flights/reserve", $payload);

            if ($response->successful()) {
                return $response->json();
            }

            // Handle PNR restrictions (403)
            if ($response->status() === 403 && $response->json('blocked')) {
                Log::error('SkyLink PNR blocked', [
                    'carrier' => $response->json('carrier'),
                    'message' => $response->json('message'),
                ]);
                throw new \Exception("PNR blocked: " . $response->json('message'));
            }

            Log::error('SkyLink reserve failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Flight reservation failed: {$response->status()}");
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
```

---

## 6. Phase 3 — Search & Results Flow

### 6.1 Update `SearchController`

**`search()` method changes:**
```php
public function search(SearchFlightRequest $request): RedirectResponse
{
    $validated = $request->validated();

    // SkyLink does not use dateWindow; remove or ignore
    // if ($validated['routeModel'] == 0 || $validated['routeModel'] == 2) {
    //     $validated['dateWindow'] = false;
    // }

    try {
        set_time_limit(300);
        $result = $this->skyLinkService->searchFlights($validated);
        
        // SkyLink returns: data.meta, data.flights[]
        $flights = $result['data']['flights'] ?? [];
        $meta = $result['data']['meta'] ?? [];
        
        $searchId = Str::uuid()->toString();
        Cache::put('flight_search_' . $searchId, [
            'flights' => $flights,
            'meta' => $meta,
            'search_data' => $validated,
        ], now()->addMinutes(60));

        session()->put('current_search_id', $searchId);
        session()->put('last_flight_search', $validated);

        return redirect()->route('search.results');
    } catch (\Exception $e) {
        Log::error("Search Error: " . $e->getMessage());
        return back()->with('error', 'Flight search failed: ' . $e->getMessage());
    }
}
```

**`results()` method changes:**
The SkyLink response is flat. You have two options:

**Option A: Mapper (Recommended)**
Create `app/Services/SkyLinkResponseMapper.php` that transforms SkyLink `flights[]` into the old `formattedFlights` + `airlineGroups` structure so existing blades need minimal changes.

**Option B: Rewrite Blades**
Update `search-result.blade.php` to render from flat SkyLink data.

**Recommended: Hybrid — Update the mapper to populate enough fields for the blades, then refine.**

```php
// In results() — transform SkyLink flights to old format
$rawOffers = $searchResults['flights'] ?? [];
$formattedFlights = [];
$airlineGroups = [];

foreach ($rawOffers as $flight) {
    $flightData = [
        'id' => $flight['booking_token'], // Use booking_token as ID
        'airline' => $flight['airline_name'],
        'airlineCode' => $flight['airline'],
        'price' => number_format($simlessPayService->convertNairaToPounds(
            $markupService->applyMarkup($flight['price'])
        )),
        'rawPrice' => $simlessPayService->convertNairaToPounds(
            $markupService->applyMarkup($flight['price'])
        ),
        'currency' => $flight['currency'] ?? 'NGN',
        'bags' => $flight['baggage'] ?? 'Check Details',
        'itineraries' => [
            [
                'depTime' => $flight['departure_time'],
                'depAirport' => $flight['departure_code'],
                'depCity' => $flight['departure_code'], // SkyLink doesn't return city names in search
                'depDate' => $flight['departure_date'] ?? $searchResults['search_data']['departureDate'],
                'arrTime' => $flight['arrival_time'],
                'arrAirport' => $flight['arrival_code'],
                'arrCity' => $flight['arrival_code'],
                'arrDate' => $flight['arrival_date'] ?? $searchResults['search_data']['departureDate'],
                'duration' => $flight['duration_time'] ?? '0h 0m',
                'durationMinutes' => 0, // Parse from duration_time if needed
                'stops' => 'Direct', // SkyLink search doesn't expose stops count
                'stopCity' => '',
                'airlineCode' => $flight['airline'],
                'airlineName' => $flight['airline_name'],
            ]
        ],
        'totalDuration' => 0,
        'rawData' => json_encode($flight),
        'allOffer' => $flight, // Pass the full flight object to verifyOffer
    ];

    $formattedFlights[] = $flightData;
    
    // Build airlineGroups...
}
```

**⚠️ Note:** If SkyLink search does not return `departure_date`/`arrival_date` explicitly, derive them from the request params. You may also need to supplement airport city names via the existing `Airport` model or a lookup table.

### 6.2 Update `verifyOffer()`
```php
public function verifyOffer(Request $request, MarkupService $markupService): RedirectResponse
{
    $validated = $request->validate(['allOffer' => 'required|string']);
    $decodedJson = urldecode($validated['allOffer']);
    $offer = json_decode($decodedJson, true);

    try {
        $pricingResult = $this->skyLinkService->verifyPrice($offer);
        
        // Store BOTH pricing data and original offer for display
        $verifyId = Str::uuid()->toString();
        Cache::put('verified_offer_' . $verifyId, [
            'pricing' => $pricingResult['data'] ?? [],
            'originalOffer' => $offer,
        ], now()->addMinutes(20));
        
        session()->put('current_verify_id', $verifyId);
        
        // Store the final verified price in session for markup
        $verifiedPrice = $pricingResult['data']['verified_price'] ?? 0;
        session()->put('markup_fee', $markupService->getMarkupFee($verifiedPrice));

        return redirect()->route('bookings.create')->with([
            'success' => 'Flight offer verified successfully.',
        ]);
    } catch (\Exception $e) {
        Log::error('Verification failed: ' . $e->getMessage());
        return back()->with('error', 'Flight offer verification failed.');
    }
}
```

---

## 7. Phase 4 — Booking & Pricing Flow

### 7.1 Update `BookingController::create()`
```php
public function create()
{
    $verifyId = session()->get('current_verify_id');
    $verifyCache = Cache::get('verified_offer_' . $verifyId);
    
    if (!$verifyCache) {
        return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
    }

    $verifiedOffer = $verifyCache['pricing'];
    $originalOffer = $verifyCache['originalOffer'];

    $searchId = session()->get('current_search_id');
    $searchData = Cache::get('flight_search_' . $searchId)['search_data'] ?? [];
    $countries = Country::orderBy('name')->get();

    return view('booking.booking', [
        'flightData' => $originalOffer, // SkyLink original flight for display
        'pricingData' => $verifiedOffer, // verified price, expiry, etc.
        'travelerCount' => $searchData['travelers']['numberOfAdults'] ?? 1,
        'routeModel' => $searchData['routeModel'] ?? 0,
        'countries' => $countries,
        'total' => ($verifiedOffer['verified_price'] ?? 0) + session()->get('markup_fee', 0),
        'taxes' => ($verifiedOffer['verified_price'] ?? 0) * 0.15 + session()->get('markup_fee', 0), // estimate if no tax field
        'simlessPayService' => $this->simlessPayService,
    ]);
}
```

### 7.2 Update `BookingController::checkout()`
This method receives passenger details, builds the SkyLink-compatible reserve payload, and stores it in cache. **It does NOT call the API.**

```php
public function checkout(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'phone' => 'nullable|string',
        'passengers' => 'required|array|min:1|max:9',
        'passengers.*.firstName' => 'required|string|max:255',
        'passengers.*.surname' => 'required|string|max:255',
        'passengers.*.dob' => 'required|date',
        'passengers.*.gender' => 'required|in:1,2,3',
        'passengers.*.title' => 'nullable|string|in:Mr,Mrs,Ms,Miss,Dr,Prof',
    ]);

    $verifyId = session()->get('current_verify_id');
    $verifyCache = Cache::get('verified_offer_' . $verifyId);
    
    if (!$verifyCache) {
        return redirect()->route('search.results')->with('error', 'Booking session expired.');
    }

    $pricingData = $verifyCache['pricing'];
    $originalOffer = $verifyCache['originalOffer'];
    
    $search = ['(', ')', '-', ' ', '+'];
    $replace = ['', '', '', ''];
    $phone = '+' . $request->countryCallingCode . str_replace($search, $replace, $request->phone);
    $countryCode = str_replace(')', '', Str::afterLast($request->countryCallingCode, '+'));

    // Build travelers for SkyLink
    $travelersPayload = [];
    $primaryGuest = null;
    $adultIndex = 0;
    $childIndex = 0;
    $infantIndex = 0;

    foreach ($request['passengers'] as $typeKey => $passenger) {
        // typeKey = "adult_1", "child_1", "infant_1"
        $travelerData = [
            'title' => $passenger['title'] ?? 'Mr',
            'first_name' => $passenger['firstName'],
            'last_name' => $passenger['surname'],
            'other_name' => $passenger['middleName'] ?? null,
            'dob' => $passenger['dob'],
            'gender' => match ($passenger['gender']) {
                '2' => 'female',
                '3' => 'male',
                default => 'male',
            },
        ];

        if (str_starts_with($typeKey, 'adult_')) {
            $key = 'adult_' . $adultIndex;
            $adultIndex++;
            
            // Primary guest = first adult
            if ($primaryGuest === null) {
                $primaryGuest = array_merge($travelerData, [
                    'email' => $request['email'],
                    'phone' => str_replace('+', '', $phone), // digits only
                    'country_code' => $countryCode,
                ]);
            }
        } elseif (str_starts_with($typeKey, 'child_')) {
            $key = 'child_' . $childIndex;
            $childIndex++;
        } else {
            $key = 'infant_' . $infantIndex;
            $infantIndex++;
        }

        $travelersPayload[$key] = $travelerData;
    }

    $searchId = session()->get('current_search_id');
    $searchData = Cache::get('flight_search_' . $searchId)['search_data'] ?? [];

    $payLoad = [
        'booking_token' => $pricingData['booking_token'],
        'passengers' => [
            'adults' => $adultIndex,
            'children' => $childIndex,
            'infants' => $infantIndex,
        ],
        'travellers' => [
            'primary_guest' => $primaryGuest,
            'travelers' => $travelersPayload,
        ],
        'ticket_time_limit_hours' => 48,
        'fare_summary' => [ // store for local booking creation
            'verified_price' => $pricingData['verified_price'] ?? 0,
            'original_price' => $pricingData['original_price'] ?? 0,
            'currency' => $pricingData['currency'] ?? 'NGN',
            'price_changed' => $pricingData['price_changed'] ?? false,
        ],
        'contact' => [
            'email' => $request['email'],
            'phone' => $phone,
            'country_code' => $countryCode,
        ],
        'flight_summary' => $originalOffer, // for display/reconstructing Itinerary
        'search_params' => $searchData,
    ];

    $bookingId = Str::uuid()->toString();
    Cache::put('booking_offer_' . $bookingId, $payLoad, now()->addMinutes(60));
    session()->put('offer_data_id', $bookingId);

    $banks = \App\Models\Bank::all();

    return view('booking.checkout', [
        'flightData' => $originalOffer,
        'pricingData' => $pricingData,
        'total' => ($pricingData['verified_price'] ?? 0) + session()->get('markup_fee', 0),
        'taxes' => ($pricingData['verified_price'] ?? 0) * 0.15 + session()->get('markup_fee', 0),
        'simlessPayService' => $this->simlessPayService,
        'banks' => $banks,
        'paymentMethod' => PaymentMethod::class,
    ]);
}
```

### 7.3 Update `BookingController::store()`
**CRITICAL:** For BNPL / Bank Transfer / Book-on-Hold, do **NOT** call SkyLink reserve. Only create a local pending booking.

```php
public function store(Request $request)
{
    $bookingId = session()->get('offer_data_id');
    $bookingOffer = Cache::get('booking_offer_' . $bookingId);

    if (!$bookingOffer) {
        return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
    }

    try {
        // For deferred-payment methods, create LOCAL booking only — no API call
        if (in_array($request->booking_type, [
            PaymentMethod::PAY_LATER->value,
            PaymentMethod::BANK_TRANSFER->value,
            PaymentMethod::BOOK_ON_HOLD->value,
        ])) {
            $booking = $this->bookingService->createPendingBookingFromSkyLinkPayload($bookingOffer);

            // Create payment record
            $method = match ($request->booking_type) {
                PaymentMethod::PAY_LATER->value => PaymentMethod::PAY_LATER,
                PaymentMethod::BANK_TRANSFER->value => PaymentMethod::BANK_TRANSFER,
                PaymentMethod::BOOK_ON_HOLD->value => PaymentMethod::BOOK_ON_HOLD,
            };

            $booking->payments()->create([
                'transaction_ref' => strtoupper($method->name) . '_' . strtoupper(uniqid()),
                'amount' => $booking->total_price,
                'currency' => $booking->currency,
                'status' => PaymentStatus::PENDING,
                'payment_method' => $method,
            ]);

            // Send appropriate email...
            // ... existing email logic ...

            session()->put('booking_id', $booking->id);
            session()->forget(['markup_fee', 'offer_data_id', 'current_verify_id']);

            return redirect()->route('bookings.confirmation')->with('success', 'Booking reserved successfully!');
        }

        // For instant payment (if any non-Stripe instant methods added), handle here
        // Currently Stripe is handled separately

    } catch (\Exception $e) {
        Log::error('Booking store failed', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Failed to reserve flight: ' . $e->getMessage());
    }
}
```

---

## 8. Phase 5 — Payment Integration

### 8.1 Stripe Flow (Instant Payment)

**Update `StripePaymentController::checkout()`**
```php
public function checkout(Request $request)
{
    $bookingId = $request->input('booking_id');

    if (!$bookingId) {
        $sessionBookingId = session()->get('offer_data_id');
        $bookingOffer = Cache::get('booking_offer_' . $sessionBookingId);

        if (!$bookingOffer) {
            return redirect()->route('search.results')->with('error', 'Booking session expired. Please re-select your flight.');
        }

        try {
            // Do NOT call SkyLink reserve here!
            // Create local pending booking using SkyLink payload
            $booking = $this->bookingService->createPendingBookingFromSkyLinkPayload($bookingOffer);
            $bookingId = $booking->id;

            session()->forget('markup_fee');
            session()->forget('offer_data_id');
        } catch (\Exception $e) {
            Log::error('Stripe pre-checkout failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to initiate booking: ' . $e->getMessage());
        }
    } else {
        $booking = Booking::findOrFail($bookingId);
    }

    session()->put('booking_id', $booking->id);

    if ($booking->status === BookingStatus::CONFIRMED) {
        return redirect()->route('bookings.confirmation')->with('success', 'Booking already confirmed.');
    }

    // Create or update Stripe payment record
    $payment = Payment::updateOrCreate(
        ['booking_id' => $booking->id, 'payment_method' => PaymentMethod::STRIPE, 'status' => PaymentStatus::PENDING],
        [
            'transaction_ref' => 'STR-' . strtoupper(Str::random(10)),
            'amount' => $booking->total_price,
            'currency' => $booking->priceInPounds->currency ?? 'GBP',
        ]
    );

    $paymentData = [
        'booking_id' => $booking->id,
        'pnr' => $booking->reference_number, // Use local ref until SkyLink PNR is obtained
        'amount' => $booking->priceInPounds->total_price,
        'currency' => $booking->priceInPounds->currency ?? 'GBP',
    ];

    $response = $this->paymentService->processStripePayment($paymentData);

    if ($response['status'] === 'success') {
        $payment->update(['stripe_session_id' => $response['session_id']]);
        return redirect()->away($response['checkout_url']);
    }

    return back()->with('error', 'Stripe payment error: ' . ($response['error'] ?? 'Unknown error'));
}
```

### 8.2 Update `PaymentService::handlePaymentSuccess()`
After Stripe confirms payment, call SkyLink reserve to get the PNR.

```php
protected function handlePaymentSuccess(array $session): array
{
    try {
        $bookingId = $session['metadata']['booking_id'] ?? null;
        if (!$bookingId) {
            throw new \Exception('No booking ID found in session metadata');
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            throw new \Exception('Booking not found');
        }

        // Retrieve the cached SkyLink payload from booking.offer_data
        $skylinkPayload = $booking->getAttribute('offer_data')['skylink_reserve_payload'] ?? null;
        
        if (!$skylinkPayload) {
            throw new \Exception('SkyLink payload not found for booking');
        }

        // Call SkyLink reserve — this generates the live PNR
        $result = app(SkyLinkApiService::class)->reserveFlight($skylinkPayload);
        
        if (!isset($result['data']['pnr'])) {
            throw new \Exception('SkyLink reserve succeeded but no PNR returned');
        }

        $pnr = $result['data']['pnr'];

        // Update payment record
        $payment = Payment::where('booking_id', $bookingId)
            ->where('payment_method', PaymentMethod::STRIPE)
            ->first();

        if ($payment) {
            $payment->update([
                'status' => PaymentStatus::COMPLETED,
                'gateway_response' => json_encode($session),
            ]);
        }

        // Update booking with PNR and confirm
        $booking->update([
            'status' => BookingStatus::CONFIRMED,
            'pnr' => $pnr,
            'ticket_issued_at' => now(),
        ]);

        // Send confirmation email
        dispatch(new SendPaymentConfirmationWithTicket($booking));

        Log::info('Stripe + SkyLink PNR success', ['booking_id' => $bookingId, 'pnr' => $pnr]);

        return ['status' => 'success', 'booking_id' => $bookingId];

    } catch (\Exception $e) {
        Log::error('Stripe webhook + SkyLink reserve failed', [
            'error' => $e->getMessage(),
            'session' => $session,
        ]);
        
        // Notify admin: Money taken but PNR creation failed
        if (isset($booking) && $booking) {
            AdminNotificationService::notifyStripeNoTicket(
                $booking, 
                'Stripe payment successful but SkyLink PNR creation failed: ' . $e->getMessage()
            );
        }
        
        return ['status' => 'error', 'message' => $e->getMessage()];
    }
}
```

---

## 9. Phase 6 — Admin Panel Updates

### 9.1 Update `AdminBookingController::update()`
When admin clicks "Confirm Booking" for a pending BNPL/Bank Transfer/Book-on-Hold, call SkyLink reserve.

```php
public function update(UpdateBookingStatusRequest $request, Booking $booking)
{
    if ($request->status === BookingStatus::CONFIRMED->value) {
        try {
            $skylinkPayload = $booking->offer_data['skylink_reserve_payload'] ?? null;
            
            if (!$skylinkPayload) {
                return back()->with('error', 'SkyLink payload missing. Cannot confirm booking.');
            }

            // Verify the booking_token is still valid (pricing expiry)
            // Optional: re-call pricing first if expired
            
            $result = app(SkyLinkApiService::class)->reserveFlight($skylinkPayload);
            
            if (!isset($result['data']['pnr'])) {
                throw new \Exception('SkyLink reserve did not return a PNR');
            }

            $booking->update([
                'status' => BookingStatus::CONFIRMED,
                'pnr' => $result['data']['pnr'],
                'ticket_issued_at' => now(),
            ]);

            // Update any pending payment
            $payment = $booking->payments()
                ->whereIn('status', [PaymentStatus::PENDING])
                ->first();
            
            if ($payment) {
                $payment->update(['status' => PaymentStatus::COMPLETED]);
            }

            dispatch(new SendPaymentConfirmationWithTicket($booking));

        } catch (\Exception $e) {
            Log::error('Admin confirm + SkyLink reserve failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to confirm booking: ' . $e->getMessage());
        }
    }

    if ($request->status === BookingStatus::CANCELLED->value) {
        $booking->update([
            'status' => BookingStatus::CANCELLED->value,
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id(),
        ]);
    }

    return back()->with('success', 'Booking status updated successfully.');
}
```

---

## 10. Phase 7 — Blade View Updates

### 10.1 `search-result.blade.php`
SkyLink returns flat flight data. Update the flight card render loop:

- Replace deep segment access (`flight.itineraries[0].segments[0].segmentDeparture.at`) with flat fields (`flight.departure_time`, `flight.departure_code`).
- Keep `allOffer` as the full SkyLink flight object encoded for the verify form.
- Add a helper to display airport city names (look up from `Airport` model if needed).

### 10.2 `booking/booking.blade.php`
- SkyLink search data is flat. Instead of `$flightData['itineraries']`, use `$flightData` directly.
- Display route: `$flightData['departure_code']` → `$flightData['arrival_code']`.
- Times: `$flightData['departure_time']`, `$flightData['arrival_time']`.
- Price: use `$pricingData['verified_price']`.
- Baggage: `$flightData['baggage']`.
- Passenger counts: derive from `$searchData['travelers']` rather than `$flightData['travelerPricings']`.

### 10.3 `booking/checkout.blade.php`
- Same updates as `booking.blade.php` for the sidebar summary.
- No structural changes needed for payment tabs.

### 10.4 `booking/confirmation.blade.php`
- Update itinerary display. If `$booking->itineraries` is populated from SkyLink reserve response, ensure the reserve response data is stored properly in the `itineraries` table.
- **If SkyLink reserve does not return full itinerary details**, keep the original flight summary stored in `offer_data` and display that.

### 10.5 `admin/bookings/show.blade.php`
- The fallback panel (when no itineraries exist) is already present. Ensure it displays correctly using `$booking->origin_location` and `$booking->origin_destination`.
- Traveler type labels: SkyLink doesn't return `travelerPricings` in the same structure. Ensure `TravelerType` enum mapping still works.

### 10.6 `booking/ticket.blade.php`
- Same as confirmation — if itineraries are missing, the fallback route banner displays.
- Ensure `$booking->pnr` is prominently shown (it comes from SkyLink reserve response).

---

## 11. Phase 8 — Database & Model Changes

### 11.1 Migration: Add `skylink_data` JSON column (Optional but recommended)
Instead of overloading `offer_data`, add a dedicated column:

```php
Schema::table('bookings', function (Blueprint $table) {
    $table->json('skylink_data')->nullable()->after('offer_data');
});
```

### 11.2 Update `Booking` model
```php
protected $fillable = [
    // ... existing fields ...
    'skylink_data',
];

protected $casts = [
    // ... existing casts ...
    'skylink_data' => 'array',
];
```

### 11.3 Update `BookingService`
Create `createPendingBookingFromSkyLinkPayload()` that populates booking from cached payload without API response:

```php
public function createPendingBookingFromSkyLinkPayload(array $payload): Booking
{
    return DB::transaction(function () use ($payload) {
        $fareSummary = $payload['fare_summary'];
        $contact = $payload['contact'];
        $flight = $payload['flight_summary'];
        $searchParams = $payload['search_params'];
        $passengers = $payload['passengers'];
        $travelers = $payload['travellers']['travelers'];
        $primaryGuest = $payload['travellers']['primary_guest'];

        $basePrice = $fareSummary['original_price'];
        $totalPrice = $fareSummary['verified_price'] + session()->get('markup_fee', 0);
        $taxesAndFees = ($fareSummary['verified_price'] - $fareSummary['original_price']) + session()->get('markup_fee', 0);
        $currency = $fareSummary['currency'];

        $booking = Booking::create([
            'user_id' => auth()->id() ?? null,
            'flight_offer_id' => $payload['booking_token'],
            'origin_location' => $flight['departure_code'] ?? ($searchParams['originLocationCode'] ?? null),
            'origin_destination' => $flight['arrival_code'] ?? ($searchParams['originDestinationCode'] ?? null),
            'carrier_code' => $flight['airline'] ?? null,
            'route_model' => match ($searchParams['routeModel'] ?? 0) {
                0 => 0, 1 => 1, 2 => 2, default => 0
            },
            'departure_date' => $searchParams['departureDate'] ?? null,
            'cabin' => $flight['class'] ?? 'Economy',
            'class' => $flight['class'] ?? 'Economy',
            'base_price' => $basePrice,
            'taxes_and_fees' => max(0, $taxesAndFees),
            'total_price' => $totalPrice,
            'markup_fee' => session()->get('markup_fee', 0),
            'contact_phone' => $contact['phone'] ?? null,
            'customer_first_name' => $primaryGuest['first_name'],
            'customer_last_name' => $primaryGuest['last_name'],
            'customer_email' => $contact['email'] ?? null,
            'currency' => $currency,
            'status' => BookingStatus::PENDING_PAYMENT,
            'expires_at' => now()->addHours(24),
            'offer_data' => [
                'skylink_reserve_payload' => $payload,
                'flight_summary' => $flight,
                'pricing' => $fareSummary,
            ],
            'skylink_data' => [
                'booking_token' => $payload['booking_token'],
                'verified_price' => $fareSummary['verified_price'],
                'price_changed' => $fareSummary['price_changed'],
            ],
        ]);

        // Create travelers from payload
        foreach ($travelers as $key => $traveler) {
            $type = match (true) {
                str_starts_with($key, 'adult') => 'ADULT',
                str_starts_with($key, 'child') => 'CHILD',
                str_starts_with($key, 'infant') => 'HELD_INFANT',
                default => 'ADULT',
            };

            $booking->travelers()->create([
                'first_name' => $traveler['first_name'],
                'last_name' => $traveler['last_name'],
                'gender' => match ($traveler['gender']) {
                    'female' => 2,
                    'male' => 3,
                    default => 1,
                },
                'date_of_birth' => $traveler['dob'],
                'email' => $traveler['email'] ?? null,
                'phone' => $traveler['phone'] ?? null,
            ]);

            // Create traveler pricings placeholder
            $booking->travelerPricings()->create([
                'traveler_id' => (string) ($key),
                'traveler_type' => $type,
                'fare_option' => 'STANDARD',
                'price' => ['base' => 0, 'total' => 0],
            ]);
        }

        // Create simplified itinerary from flight summary
        $booking->itineraries()->create([
            'itinerary_title' => match ($booking->route_model) {
                1 => 'Outbound',
                default => 'Flight 1',
            },
            'itinerary_summary' => ($flight['departure_code'] ?? '') . ' to ' . ($flight['arrival_code'] ?? ''),
            'itinerary_index' => 1,
            'duration' => $flight['duration_time'] ?? null,
            'duration_in_minutes' => 0, // parse if needed
            'segments' => [
                [
                    'carrier' => ['iataCode' => $flight['airline'], 'name' => $flight['airline_name']],
                    'number' => $flight['flight_no'] ?? '',
                    'segmentDeparture' => [
                        'at' => ($searchParams['departureDate'] ?? '') . ' ' . ($flight['departure_time'] ?? ''),
                        'airport' => ['iataCode' => $flight['departure_code'], 'city' => $flight['departure_code']],
                    ],
                    'segmentArrival' => [
                        'at' => ($searchParams['departureDate'] ?? '') . ' ' . ($flight['arrival_time'] ?? ''),
                        'airport' => ['iataCode' => $flight['arrival_code'], 'city' => $flight['arrival_code']],
                    ],
                ]
            ],
        ]);

        // GBP prices
        $booking->priceInPounds()->create([
            'currency' => 'GBP',
            'price' => number_format($this->simlessPayService->convertNairaToPounds($basePrice)),
            'tax' => number_format($this->simlessPayService->convertNairaToPounds(max(0, $taxesAndFees))),
            'markup' => number_format($this->simlessPayService->convertNairaToPounds(session()->get('markup_fee', 0))),
            'total_price' => number_format($this->simlessPayService->convertNairaToPounds($totalPrice)),
        ]);

        AdminNotificationService::notifyNewReservation($booking);

        return $booking;
    });
}
```

---

## 12. Phase 9 — Error Handling & Reliability

### 12.1 JWT Token Mid-Flow Failure
If token expires during a critical operation (e.g., reserve after payment):
- `SkyLinkAuthService` proactively refreshes tokens 2 minutes before expiry.
- If a 401 occurs mid-flow, catch it, clear cache, retry once.

### 12.2 Price Expiry
SkyLink pricing expires at `expires_at`. Store this timestamp in the booking cache. Before calling reserve:
- Check if `expires_at` is still valid.
- If expired, call pricing again with the original booking_token (if SkyLink allows re-pricing) or fail gracefully with "Please search again".

### 12.3 502 Bad Gateway (Supplier Error)
SkyLink returns 502 when the underlying GDS/NDC supplier is down.
- Catch 502s specifically.
- Show user-friendly message: "The airline booking system is temporarily unavailable. Please try again in a few moments."
- Log for admin alerts.

### 12.4 403 PNR Restriction
If a carrier is blocked, SkyLink returns 403 with `blocked: true`.
- Display: "Reservations on [Carrier] are temporarily unavailable. Please select a different flight."
- Do not retry.

### 12.5 Rate Limits
| Endpoint | Limit |
|----------|-------|
| Search | 60/min |
| Pricing | 30/min |
| Reserve | 10/min |

Implement request throttling in the frontend (disable button for 1s after click) and backend (Laravel rate limiter on search routes).

---

## 13. Phase 10 — Testing & Rollback

### 13.1 Testing Checklist
- [ ] JWT login and token refresh work correctly.
- [ ] Search one-way, round-trip, and multi-city return results.
- [ ] Price verification updates booking_token.
- [ ] Passenger form submits and builds correct SkyLink payload.
- [ ] Stripe flow: payment succeeds → webhook fires → SkyLink reserve called → PNR stored → booking confirmed.
- [ ] BNPL flow: local booking created → no SkyLink call → admin confirm triggers SkyLink reserve → PNR stored.
- [ ] Bank Transfer flow: same as BNPL.
- [ ] Book-on-Hold flow: same as BNPL.
- [ ] Admin booking show page displays correctly with and without itineraries.
- [ ] Ticket PDF downloads and shows PNR.
- [ ] Email confirmations include PNR.
- [ ] Fallback when SkyLink is down: admin notified, user sees error.

### 13.2 Rollback Plan
1. Keep `FlexiApiService.php` intact during development.
2. Implement SkyLink services in parallel.
3. Use a **feature flag** (e.g., `config('features.skylink_enabled')`) to switch between FlexiAPI and SkyLink.
4. If issues occur in production, toggle the flag to instantly revert to FlexiAPI.
5. Only delete FlexiApiService after 2 weeks of stable SkyLink operation.

### 13.3 Suggested Feature Flag
```php
// config/features.php
return [
    'skylink_enabled' => env('FEATURE_SKYLINK_ENABLED', false),
];
```

Inject a service provider or factory that returns `SkyLinkApiService` when enabled, otherwise `FlexiApiService`.

---

## 14. Security Checklist

- [ ] `247TRAVELS_PASSWORD` is not logged anywhere.
- [ ] JWT tokens are stored in encrypted cache (use `CACHE_STORE=database` or Redis with encryption).
- [ ] `booking_token` is never exposed to the browser JavaScript console (hidden inputs only).
- [ ] SkyLink reserve payload (with passenger PII) is stored encrypted or at minimum in the protected `offer_data` JSON column.
- [ ] Rate limiting on `/api/login` proxy if implemented.
- [ ] All SkyLink requests use HTTPS (validated by HTTP client).

---

## 15. Summary of Files to Create / Modify

### Create
1. `app/Services/SkyLinkAuthService.php`
2. `app/Services/SkyLinkApiService.php`
3. `app/Services/SkyLinkResponseMapper.php` (optional, for backward-compatible view data)

### Modify
1. `config/247travels.php`
2. `app/Http/Controllers/SearchController.php`
3. `app/Http/Controllers/BookingController.php`
4. `app/Http/Controllers/StripePaymentController.php`
5. `app/Http/Controllers/Admin/AdminBookingController.php`
6. `app/Services/BookingService.php`
7. `app/Services/PaymentService.php`
8. `app/Models/Booking.php`
9. `resources/views/search-result.blade.php`
10. `resources/views/booking/booking.blade.php`
11. `resources/views/booking/checkout.blade.php`
12. `resources/views/booking/confirmation.blade.php`
13. `resources/views/admin/bookings/show.blade.php`
14. `resources/views/booking/ticket.blade.php`
15. `.env.example`
16. Add database migration for `skylink_data` column

---

*End of Implementation Guide*
