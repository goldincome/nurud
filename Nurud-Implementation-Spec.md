# **Programmatic SEO Implementation Spec — Nurud.com**

**Stack:** Laravel 10 (Standalone Installation)

**Target Domain:** https://nurud.com

**Audience:** Designed to be executed directly by an AI coding agent or engineer. Every phase provides exact file paths, complete code, and acceptance criteria.

## **1\. Key Architectural Decisions (Nurud)**

### **1.1 URL Slug Format for Flight Routes**

Canonical format: {origin-city}-{origin-iata}-to-{destination-city}-{destination-iata} (e.g., london-lhr-to-lagos-los).

* **Reasoning:** A simple city-pair slug like london-to-lagos breaks when multiple airports serve a metropolitan area (LHR vs LGW vs STN). The IATA-qualified format prevents slug collision while retaining strong SEO value.

### **1.2 Route Data Storage: JSON Metadata vs. Relational Airline Tables**

Store airlines\_operating, faqs, and seasonal tips inside the search\_intent\_metadata JSON column on the flight\_routes table.

* **Reasoning:** Avoids premature relational over-engineering (airlines, flight\_route\_airline pivot tables) during the v1 launch phase while maintaining flexible, rich schema generation.

### **1.3 Entity Separation & Hero Cleanliness**

Move the "Get Your UK Virtual Address" cross-sell out of the primary flight search hero into a dedicated "Sister Services for the UK-Nigeria Community" card section lower on the homepage and within the /services directory.

* **Reasoning:** Diluting the flight-search hero confuses search engine topic clustering regarding Nurud's primary commercial entity (travel agency).

### **1.4 Static Streamed XML Sitemap**

Sitemaps are generated on disk using an Artisan command streaming via XMLWriter to public/sitemap.xml.

* **Reasoning:** Dynamic XML controller routes run the risk of memory exhaustion and lack cache efficiency. The dynamic /flights/sitemap-routes.xml route is discarded in favor of scheduled disk generation.

## **2\. Phase 0 — Immediate Fixes on Existing Pages (No Schema Changes)**

Complete these items prior to implementing programmatic routes:

| \# | File / Location | Required Action |
| :---- | :---- | :---- |
| **A0.1** | resources/views/layouts/app.blade.php | Replace hardcoded blank meta tags (- Nurud Travels) with dynamic \<x-seo-meta\> component data. |
| **A0.2** | routes/web.php (/login, /register) | Add \-\>middleware('noindex') or set \<meta name="robots" content="noindex, follow"\>. Utility pages must not consume crawl budget. |
| **A0.3** | Homepage view | Remove placeholder "Trending cities" and "Trending countries" cards pointing to \# dead anchors; wire them only to real flight routes. |
| **A0.4** | Homepage view | Remove dummy "Gatún, Panama" cards and foreign stock placeholders; replace with primary UK ↔ Nigeria routes. |
| **A0.5** | Homepage view | Demote the "Get Your UK Virtual Address" banner from the hero into the lower "Sister Services" section alongside NIN/BVN, Passport, and TIN links. |
| **A0.6** | Asset directory | Replace hotlinked Unsplash assets with locally hosted, WebP-compressed images to safeguard Core Web Vitals (LCP). |

**Acceptance Check:** Every indexed page has a unique \<title\> and non-empty \<meta name="description"\>; zero \#-only anchor cards exist; hero remains 100% flight-intent focused.

## **3\. Shared SEO Blade Components**

### **3.1 Meta Tag Component**

resources/views/components/seo-meta.blade.php

@props(\[  
    'title',  
    'description',  
    'canonical' \=\> url()-\>current(),  
    'robots' \=\> 'index, follow',  
    'ogImage' \=\> asset('images/og-default.jpg'),  
\])  
\<title\>{{ $title }}\</title\>  
\<meta name="description" content="{{ $description }}"\>  
\<link rel="canonical" href="{{ $canonical }}"\>  
\<meta name="robots" content="{{ $robots }}"\>  
\<meta property="og:type" content="website"\>  
\<meta property="og:title" content="{{ $title }}"\>  
\<meta property="og:description" content="{{ $description }}"\>  
\<meta property="og:url" content="{{ $canonical }}"\>  
\<meta property="og:image" content="{{ $ogImage }}"\>  
\<meta name="twitter:card" content="summary\_large\_image"\>

### **3.2 JSON-LD Schema Component**

resources/views/components/json-ld.blade.php

@props(\['schema'\])  
\<script type="application/ld+json"\>  
{\!\! json\_encode($schema, JSON\_UNESCAPED\_SLASHES | JSON\_UNESCAPED\_UNICODE) \!\!}  
\</script\>

## **4\. Database Migrations & Eloquent Models**

### **4.1 Airports Migration**

database/migrations/2026\_01\_01\_000001\_create\_airports\_table.php

use Illuminate\\Database\\Migrations\\Migration;  
use Illuminate\\Database\\Schema\\Blueprint;  
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration {  
    public function up(): void {  
        Schema::create('airports', function (Blueprint $table) {  
            $table-\>id();  
            $table-\>string('iata\_code', 3)-\>unique()-\>index();  
            $table-\>string('icao\_code', 4)-\>nullable();  
            $table-\>string('name');  
            $table-\>string('city');  
            $table-\>string('country');  
            $table-\>string('country\_code', 2);  
            $table-\>string('slug')-\>unique();  
            $table-\>decimal('latitude', 10, 7)-\>nullable();  
            $table-\>decimal('longitude', 10, 7)-\>nullable();  
            $table-\>timestamps();  
        });  
    }

    public function down(): void {  
        Schema::dropIfExists('airports');  
    }  
};

### **4.2 Flight Routes Migration**

database/migrations/2026\_01\_01\_000002\_create\_flight\_routes\_table.php

use Illuminate\\Database\\Migrations\\Migration;  
use Illuminate\\Database\\Schema\\Blueprint;  
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration {  
    public function up(): void {  
        Schema::create('flight\_routes', function (Blueprint $table) {  
            $table-\>id();  
            $table-\>foreignId('origin\_airport\_id')-\>constrained('airports')-\>cascadeOnDelete();  
            $table-\>foreignId('destination\_airport\_id')-\>constrained('airports')-\>cascadeOnDelete();  
            $table-\>string('slug')-\>unique()-\>index(); // e.g. london-lhr-to-lagos-los  
            $table-\>unsignedInteger('distance\_miles')-\>nullable();  
            $table-\>unsignedInteger('avg\_duration\_minutes')-\>nullable();  
            $table-\>boolean('has\_direct\_flights')-\>default(true);  
            $table-\>decimal('lowest\_fare\_gbp', 8, 2)-\>index();  
            $table-\>string('primary\_airline')-\>nullable();  
            $table-\>boolean('is\_indexable')-\>default(true)-\>index();  
            $table-\>json('search\_intent\_metadata')-\>nullable(); // Holds faqs\[\], airlines\_operating\[\], seasonal\_tips\[\]  
            $table-\>timestamps();  
            $table-\>unique(\['origin\_airport\_id', 'destination\_airport\_id'\]);  
        });  
    }

    public function down(): void {  
        Schema::dropIfExists('flight\_routes');  
    }  
};

### **4.3 Airport Model**

app/Models/Airport.php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;  
use Illuminate\\Database\\Eloquent\\Relations\\HasMany;

class Airport extends Model  
{  
    protected $fillable \= \[  
        'iata\_code',  
        'icao\_code',  
        'name',  
        'city',  
        'country',  
        'country\_code',  
        'slug',  
        'latitude',  
        'longitude',  
    \];

    public function outboundRoutes(): HasMany  
    {  
        return $this-\>hasMany(FlightRoute::class, 'origin\_airport\_id');  
    }

    public function inboundRoutes(): HasMany  
    {  
        return $this-\>hasMany(FlightRoute::class, 'destination\_airport\_id');  
    }  
}

### **4.4 FlightRoute Model**

app/Models/FlightRoute.php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;  
use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;

class FlightRoute extends Model  
{  
    protected $fillable \= \[  
        'origin\_airport\_id',  
        'destination\_airport\_id',  
        'slug',  
        'distance\_miles',  
        'avg\_duration\_minutes',  
        'has\_direct\_flights',  
        'lowest\_fare\_gbp',  
        'primary\_airline',  
        'is\_indexable',  
        'search\_intent\_metadata',  
    \];

    protected $casts \= \[  
        'search\_intent\_metadata' \=\> 'array',  
        'has\_direct\_flights' \=\> 'boolean',  
        'is\_indexable' \=\> 'boolean',  
    \];

    public function originAirport(): BelongsTo  
    {  
        return $this-\>belongsTo(Airport::class, 'origin\_airport\_id');  
    }

    public function destinationAirport(): BelongsTo  
    {  
        return $this-\>belongsTo(Airport::class, 'destination\_airport\_id');  
    }  
}

## **5\. Web Routes**

Append to routes/web.php:

use App\\Http\\Controllers\\FlightRouteController;  
use Illuminate\\Support\\Facades\\Route;

// Programmatic Flight Routes  
Route::prefix('flights')-\>group(function () {  
    Route::get('/{route\_slug}', \[FlightRouteController::class, 'show'\])  
        \-\>where('route\_slug', '^\[a-z0-9-\]+-\[a-z0-9\]{3}-to-\[a-z0-9-\]+-\[a-z0-9\]{3}$')  
        \-\>name('flights.route.show');  
});

// Static Diaspora Service Pages  
Route::view('/services', 'services.index')-\>name('services.index');  
Route::view('/services/nin-bvn-enrollment-uk', 'services.nin-bvn')-\>name('services.nin-bvn');  
Route::view('/services/nigerian-passport-renewal-london', 'services.passport')-\>name('services.passport');  
Route::view('/services/tin-registration-uk', 'services.tin')-\>name('services.tin');  
Route::view('/services/book-now-pay-later-flights', 'services.bnpl')-\>name('services.bnpl');  
Route::view('/services/travel-insurance-nigeria', 'services.insurance')-\>name('services.insurance');

// Topical Travel Guide Silo  
Route::view('/travel-guides', 'guides.index')-\>name('guides.index');  
Route::view('/travel-guides/nigeria-entry-visa-requirements-uk', 'guides.visa-requirements')-\>name('guides.visa-requirements');  
Route::view('/travel-guides/lagos-murtala-muhammed-airport-guide', 'guides.lagos-airport')-\>name('guides.lagos-airport');  
Route::view('/travel-guides/how-to-renew-nigerian-passport-from-uk', 'guides.passport-renewal')-\>name('guides.passport-renewal');  
Route::view('/travel-guides/nin-bvn-enrolment-centres-uk', 'guides.nin-bvn-centres')-\>name('guides.nin-bvn-centres');  
Route::view('/travel-guides/best-time-to-fly-london-to-lagos', 'guides.best-time-to-fly')-\>name('guides.best-time-to-fly');

// Local Woolwich Physical Presence Page  
Route::view('/travel-agent-woolwich-london', 'local.woolwich')-\>name('local.woolwich');

## **6\. Controller Implementation**

app/Http/Controllers/FlightRouteController.php

namespace App\\Http\\Controllers;

use App\\Models\\FlightRoute;  
use Illuminate\\Support\\Facades\\Cache;  
use Illuminate\\View\\View;

class FlightRouteController extends Controller  
{  
    public function show(string $route\_slug): View  
    {  
        $data \= Cache::remember("pseo\_flight\_route\_{$route\_slug}", now()-\>addHours(24), function () use ($route\_slug) {  
            $route \= FlightRoute::with(\['originAirport', 'destinationAirport'\])  
                \-\>where('slug', $route\_slug)  
                \-\>where('is\_indexable', true)  
                \-\>firstOrFail();

            return $this-\>buildRouteDataPayload($route);  
        });

        return view('flights.show', \['page' \=\> $data\]);  
    }

    private function buildRouteDataPayload(FlightRoute $route): array  
    {  
        $origin \= $route-\>originAirport;  
        $dest \= $route-\>destinationAirport;  
        $meta \= $route-\>search\_intent\_metadata ?? \[\];  
        $faqs \= $meta\['faqs'\] ?? \[\];  
        $airlines \= $meta\['airlines\_operating'\] ?? \[\];

        $payload \= \[  
            'meta' \=\> \[  
                'title' \=\> "Cheap Flights from {$origin-\>city} ({$origin-\>iata\_code}) to {$dest-\>city} ({$dest-\>iata\_code}) | From £{$route-\>lowest\_fare\_gbp} — Nurud Travels",  
                'description' \=\> "Compare flight deals from {$origin-\>city} to {$dest-\>city}. " .  
                    ($route-\>has\_direct\_flights ? 'Direct flights available. ' : '') .  
                    "Lowest fare from £{$route-\>lowest\_fare\_gbp}. Book securely with Nurud Travels.",  
                'canonical' \=\> route('flights.route.show', \['route\_slug' \=\> $route-\>slug\]),  
                'robots' \=\> 'index, follow',  
            \],  
            'route' \=\> \[  
                'slug' \=\> $route-\>slug,  
                'origin\_city' \=\> $origin-\>city,  
                'origin\_code' \=\> $origin-\>iata\_code,  
                'origin\_airport' \=\> $origin-\>name,  
                'destination\_city' \=\> $dest-\>city,  
                'destination\_code' \=\> $dest-\>iata\_code,  
                'destination\_airport' \=\> $dest-\>name,  
                'distance' \=\> $route-\>distance\_miles ? "{$route-\>distance\_miles} miles" : null,  
                'duration' \=\> $route-\>avg\_duration\_minutes  
                    ? floor($route-\>avg\_duration\_minutes / 60\) . 'h ' . ($route-\>avg\_duration\_minutes % 60\) . 'm'  
                    : null,  
                'has\_direct' \=\> $route-\>has\_direct\_flights,  
                'lowest\_fare' \=\> $route-\>lowest\_fare\_gbp,  
                'currency' \=\> 'GBP',  
            \],  
            'airlines\_operating' \=\> $airlines,  
            'faqs' \=\> $faqs,  
        \];

        $payload\['schema'\] \= $this-\>generateJsonLdSchema($route, $payload);

        return $payload;  
    }

    private function generateJsonLdSchema(FlightRoute $route, array $payload): array  
    {  
        $graph \= \[  
            \[  
                '@type' \=\> 'Flight',  
                'provider' \=\> \[  
                    '@type' \=\> 'TravelAgency',  
                    'name' \=\> 'Nurud Travels',  
                    'url' \=\> config('app.url'),  
                \],  
                'departureAirport' \=\> \[  
                    '@type' \=\> 'Airport',  
                    'name' \=\> $route-\>originAirport-\>name,  
                    'iataCode' \=\> $route-\>originAirport-\>iata\_code,  
                \],  
                'arrivalAirport' \=\> \[  
                    '@type' \=\> 'Airport',  
                    'name' \=\> $route-\>destinationAirport-\>name,  
                    'iataCode' \=\> $route-\>destinationAirport-\>iata\_code,  
                \],  
            \],  
            \[  
                '@type' \=\> 'AggregateOffer',  
                'priceCurrency' \=\> 'GBP',  
                'lowPrice' \=\> $route-\>lowest\_fare\_gbp,  
                'offerCount' \=\> count($payload\['airlines\_operating'\]) ?: 1,  
                'url' \=\> route('flights.route.show', \['route\_slug' \=\> $route-\>slug\]),  
            \],  
        \];

        if (\!empty($payload\['faqs'\])) {  
            $graph\[\] \= \[  
                '@type' \=\> 'FAQPage',  
                'mainEntity' \=\> array\_map(fn ($f) \=\> \[  
                    '@type' \=\> 'Question',  
                    'name' \=\> $f\['question'\],  
                    'acceptedAnswer' \=\> \['@type' \=\> 'Answer', 'text' \=\> $f\['answer'\]\],  
                \], $payload\['faqs'\]),  
            \];  
        }

        return \['@context' \=\> 'https://schema.org', '@graph' \=\> $graph\];  
    }  
}

## **7\. Blade View Template**

resources/views/flights/show.blade.php

@extends('layouts.app')

@section('head')  
    \<x-seo-meta :title="$page\['meta'\]\['title'\]" :description="$page\['meta'\]\['description'\]" :canonical="$page\['meta'\]\['canonical'\]" :robots="$page\['meta'\]\['robots'\]" /\>  
    \<x-json-ld :schema="$page\['schema'\]" /\>  
@endsection

@section('content')  
\<main class="max-w-7xl mx-auto px-4 py-8"\>  
    \<header class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100"\>  
        \<h1 class="text-3xl font-bold text-gray-900"\>  
            Flights from {{ $page\['route'\]\['origin\_city'\] }} ({{ $page\['route'\]\['origin\_code'\] }}) to {{ $page\['route'\]\['destination\_city'\] }} ({{ $page\['route'\]\['destination\_code'\] }})  
        \</h1\>  
        \<p class="mt-2 text-gray-600"\>  
            Compare fares. {{ $page\['route'\]\['has\_direct'\] ? 'Direct flights available' : 'Connecting flights available' }} from  
            \<span class="font-bold text-emerald-600"\>£{{ $page\['route'\]\['lowest\_fare'\] }}\</span\>.  
        \</p\>  
    \</header\>

    \<section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8"\>  
        \<div class="bg-white p-4 rounded-lg border border-gray-100"\>  
            \<span class="text-xs text-gray-500 uppercase font-semibold"\>Average Flight Time\</span\>  
            \<p class="text-lg font-bold text-gray-800"\>{{ $page\['route'\]\['duration'\] ?? '—' }}\</p\>  
        \</div\>  
        \<div class="bg-white p-4 rounded-lg border border-gray-100"\>  
            \<span class="text-xs text-gray-500 uppercase font-semibold"\>Distance\</span\>  
            \<p class="text-lg font-bold text-gray-800"\>{{ $page\['route'\]\['distance'\] ?? '—' }}\</p\>  
        \</div\>  
        \<div class="bg-white p-4 rounded-lg border border-gray-100"\>  
            \<span class="text-xs text-gray-500 uppercase font-semibold"\>Direct Flight Status\</span\>  
            \<p class="text-lg font-bold text-gray-800"\>{{ $page\['route'\]\['has\_direct'\] ? 'Available' : 'Connecting Only' }}\</p\>  
        \</div\>  
        \<div class="bg-white p-4 rounded-lg border border-gray-100"\>  
            \<span class="text-xs text-gray-500 uppercase font-semibold"\>Lowest Recorded Fare\</span\>  
            \<p class="text-lg font-bold text-emerald-600"\>£{{ $page\['route'\]\['lowest\_fare'\] }}\</p\>  
        \</div\>  
    \</section\>

    @if(\!empty($page\['airlines\_operating'\]))  
    \<section class="mb-8"\>  
        \<h2 class="text-xl font-bold mb-4"\>Airlines Operating This Route\</h2\>  
        \<table class="w-full text-sm border border-gray-100 rounded-lg overflow-hidden"\>  
            \<thead class="bg-gray-50"\>  
                \<tr\>  
                    \<th class="text-left p-3"\>Airline\</th\>  
                    \<th class="text-left p-3"\>Type\</th\>  
                    \<th class="text-left p-3"\>From\</th\>  
                \</tr\>  
            \</thead\>  
            \<tbody\>  
            @foreach($page\['airlines\_operating'\] as $airline)  
                \<tr class="border-t"\>  
                    \<td class="p-3"\>{{ $airline\['name'\] }}\</td\>  
                    \<td class="p-3"\>{{ $airline\['flight\_type'\] }}\</td\>  
                    \<td class="p-3"\>£{{ $airline\['min\_price'\] }}\</td\>  
                \</tr\>  
            @endforeach  
            \</tbody\>  
        \</table\>  
    \</section\>  
    @endif

    @if(\!empty($page\['faqs'\]))  
    \<section class="mb-8"\>  
        \<h2 class="text-xl font-bold mb-4"\>Frequently Asked Questions\</h2\>  
        @foreach($page\['faqs'\] as $faq)  
            \<details class="bg-white p-4 rounded-lg border border-gray-100 mb-2"\>  
                \<summary class="font-semibold cursor-pointer"\>{{ $faq\['question'\] }}\</summary\>  
                \<p class="mt-2 text-gray-600"\>{{ $faq\['answer'\] }}\</p\>  
            \</details\>  
        @endforeach  
    \</section\>  
    @endif

    {{-- Cross-sell Section: Cleanly positioned below flight content \--}}  
    \<section class="bg-indigo-50 rounded-xl p-6"\>  
        \<h2 class="text-lg font-bold mb-2"\>Travelling to Nigeria? We can help with more than flights.\</h2\>  
        \<div class="flex flex-wrap gap-3 text-sm"\>  
            \<a href="{{ route('services.nin-bvn') }}" class="underline"\>NIN/BVN Enrolment\</a\>  
            \<a href="{{ route('services.passport') }}" class="underline"\>Nigerian Passport Renewal\</a\>  
            \<a href="{{ route('services.tin') }}" class="underline"\>Tax ID (TIN) Registration\</a\>  
            \<a href="https://charltonvirtualoffice.com" class="underline" target="\_blank" rel="noopener"\>UK Virtual Business Address\</a\>  
        \</div\>  
    \</section\>  
\</main\>  
@endsection

## **8\. Topical Content Silo Structure**

nurud.com/  
├── flights/{origin-city}-{IATA}-to-{destination-city}-{IATA}   (pSEO, DB-driven)  
├── services/  
│   ├── nin-bvn-enrollment-uk  
│   ├── nigerian-passport-renewal-london  
│   ├── tin-registration-uk  
│   ├── book-now-pay-later-flights  
│   └── travel-insurance-nigeria  
├── travel-guides/  
│   ├── nigeria-entry-visa-requirements-uk  
│   ├── lagos-murtala-muhammed-airport-guide  
│   ├── how-to-renew-nigerian-passport-from-uk  
│   ├── nin-bvn-enrolment-centres-uk  
│   └── best-time-to-fly-london-to-lagos  
└── travel-agent-woolwich-london   (Local SEO page, linked to Woolwich GBP)

**Content Requirements for Static Views:**

* Minimum 400+ words of distinct text per guide/service view.  
* Internal links mapped to:  
  1. Relevant flight route landing pages.  
  2. Transactional partner domain ninuk.co.uk for NIN/BVN and passport services.  
  3. Sister service https://charltonvirtualoffice.com for UK company formation and virtual address needs.

## **9\. Launch Seed Data (Minimum 6 Routes)**

Create database/seeders/FlightRouteSeeder.php with these core routes:

1. **London Heathrow (LHR) ↔ Lagos (LOS)**: Direct options available, Air Peace / British Airways / Virgin Atlantic.  
2. **London Heathrow (LHR) ↔ Abuja (ABV)**: British Airways.  
3. **Manchester (MAN) ↔ Lagos (LOS)**: Connecting options (Qatar, Turkish Airlines, Air France).  
4. **London Heathrow (LHR) ↔ Accra (ACC)**: British Airways.  
5. **London Heathrow (LHR) ↔ Dubai (DXB)**: Emirates, British Airways.  
6. **London Heathrow (LHR) ↔ Johannesburg (JNB)**: Virgin Atlantic, British Airways.

*Constraint:* Ensure each seeded route contains populated search\_intent\_metadata with at least 3 FAQs and 2 airlines before public release.

## **10\. Sitemap & Robots Configuration**

### **10.1 Dedicated Nurud Sitemap Generator Command**

app/Console/Commands/GenerateSitemapCommand.php

namespace App\\Console\\Commands;

use App\\Models\\FlightRoute;  
use Carbon\\Carbon;  
use Illuminate\\Console\\Command;  
use Illuminate\\Support\\Facades\\File;  
use XMLWriter;

class GenerateSitemapCommand extends Command  
{  
    protected $signature \= 'sitemap:generate';  
    protected $description \= 'Generate a streaming XML sitemap for Nurud.com';

    public function handle(): int  
    {  
        $baseUrl \= rtrim(config('app.url', 'https://nurud.com'), '/');  
        $destinationPath \= public\_path('sitemap.xml');

        $this-\>info('Generating sitemap for Nurud.com...');

        File::ensureDirectoryExists(dirname($destinationPath));  
        $writer \= new XMLWriter();  
        $writer-\>openURI($destinationPath);  
        $writer-\>startDocument('1.0', 'UTF-8');  
        $writer-\>setIndent(true);  
        $writer-\>startElement('urlset');  
        $writer-\>writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        $staticRoutes \= \[  
            \['/', 'daily', '1.0'\],  
            \['/about', 'monthly', '0.5'\],  
            \['/faq', 'weekly', '0.6'\],  
            \['/contact', 'monthly', '0.5'\],  
            \['/services', 'weekly', '0.7'\],  
            \['/services/nin-bvn-enrollment-uk', 'weekly', '0.8'\],  
            \['/services/nigerian-passport-renewal-london', 'weekly', '0.8'\],  
            \['/services/tin-registration-uk', 'weekly', '0.8'\],  
            \['/services/book-now-pay-later-flights', 'weekly', '0.7'\],  
            \['/services/travel-insurance-nigeria', 'weekly', '0.7'\],  
            \['/travel-guides', 'weekly', '0.6'\],  
            \['/travel-guides/nigeria-entry-visa-requirements-uk', 'monthly', '0.7'\],  
            \['/travel-guides/lagos-murtala-muhammed-airport-guide', 'monthly', '0.7'\],  
            \['/travel-guides/how-to-renew-nigerian-passport-from-uk', 'monthly', '0.7'\],  
            \['/travel-guides/nin-bvn-enrolment-centres-uk', 'monthly', '0.7'\],  
            \['/travel-guides/best-time-to-fly-london-to-lagos', 'monthly', '0.7'\],  
            \['/travel-agent-woolwich-london', 'monthly', '0.6'\],  
        \];

        foreach ($staticRoutes as \[$path, $freq, $priority\]) {  
            $this-\>addUrlElement($writer, $baseUrl . $path, now(), $freq, $priority);  
        }

        $routeCount \= 0;  
        FlightRoute::where('is\_indexable', true)  
            \-\>select(\['slug', 'updated\_at'\])  
            \-\>chunk(500, function ($routes) use ($writer, $baseUrl, &$routeCount) {  
                foreach ($routes as $route) {  
                    $this-\>addUrlElement(  
                        $writer,  
                        "{$baseUrl}/flights/{$route-\>slug}",  
                        $route-\>updated\_at,  
                        'daily',  
                        '0.9'  
                    );  
                    $routeCount++;  
                }  
            });

        $writer-\>endElement();  
        $writer-\>endDocument();  
        $writer-\>flush();

        $this-\>info("Nurud sitemap generated ({$routeCount} programmatic routes written to public/sitemap.xml).");  
        return self::SUCCESS;  
    }

    private function addUrlElement(XMLWriter $writer, string $url, ?Carbon $lastMod, string $changeFreq, string $priority): void  
    {  
        $writer-\>startElement('url');  
        $writer-\>writeElement('loc', htmlspecialchars($url, ENT\_XML1, 'UTF-8'));  
        $writer-\>writeElement('lastmod', ($lastMod ?? now())-\>toIso8601String());  
        $writer-\>writeElement('changefreq', $changeFreq);  
        $writer-\>writeElement('priority', $priority);  
        $writer-\>endElement();  
    }  
}

### **10.2 Cron Schedule**

app/Console/Kernel.php

protected function schedule(Schedule $schedule): void  
{  
    $schedule-\>command('sitemap:generate')  
        \-\>dailyAt('02:00')  
        \-\>withoutOverlapping()  
        \-\>runInBackground();  
}

### **10.3 robots.txt**

public/robots.txt

User-agent: \*  
Allow: /  
Disallow: /login  
Disallow: /register  
Disallow: /admin/

Sitemap: https://nurud.com/sitemap.xml

## **11\. Cross-Site Interlinking Rules**

* **To Charlton Virtual Office:** Maintain the homepage cross-sell card within "Sister Services" and reference https://charltonvirtualoffice.com in /services and relevant travel guides ("Setting up a UK company or need a registered address while abroad? Visit Charlton Virtual Office").  
* **To NIN/BVN Partner:** Route users to https://ninuk.co.uk directly from /services/nin-bvn-enrollment-uk and /services/nigerian-passport-renewal-london.

## **12\. QA & Verification Checklist**

1. **Rich Results:** Run sample flight routes (/flights/london-lhr-to-lagos-los) through Google Rich Results Test; verify Flight, AggregateOffer, and FAQPage schemas have zero errors.  
2. **Meta Tags:** Verify all routes output a distinct title and description via \<x-seo-meta\>.  
3. **Noindex Verification:** Confirm /login and /register have \<meta name="robots" content="noindex, follow"\>.  
4. **Sitemap Generation:** Execute php artisan sitemap:generate and confirm valid XML syntax at public/sitemap.xml.  
5. **Google Business Profile:** Ensure Nurud's Woolwich listing is strictly categorized as a **Travel Agency** to prevent Google suspension from co-locating at the same physical unit as Charlton Virtual Office.

## **13\. Master Task Checklist**

* \[ \] Execute Phase 0 fixes (A0.1 to A0.6).  
* \[ \] Create Blade components: resources/views/components/seo-meta.blade.php and json-ld.blade.php.  
* \[ \] Run migrations: airports and flight\_routes.  
* \[ \] Implement models: Airport and FlightRoute.  
* \[ \] Register routes in routes/web.php.  
* \[ \] Implement FlightRouteController.  
* \[ \] Implement resources/views/flights/show.blade.php.  
* \[ \] Seed launch routes with search\_intent\_metadata filled.  
* \[ \] Implement GenerateSitemapCommand and register in Kernel.php.  
* \[ \] Deploy public/robots.txt.  
* \[ \] Execute QA test suite.