<?php

use App\Models\FlightRoute;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

it('renders indexable front pages with expected title and robots', function () {
    $routes = [
        '/' => ['search-title', 'Book Cheap Flights to Nigeria & Worldwide | Nurud Travels'],
        '/login' => ['search-title', 'Login | Nurud Travels'],
        '/register' => ['search-title', 'Create Account | Nurud Travels'],
        '/about' => ['search-title', 'About Nurud Travels | Your London Travel Agency'],
        '/contact' => ['search-title', 'Contact Nurud Travels | Flights to Nigeria & Worldwide'],
        '/faq' => ['search-title', 'Flight Booking FAQs | Nurud Travels'],
        '/privacy' => ['search-title', 'Privacy Policy | Nurud Travels'],
        '/terms' => ['search-title', 'Terms and Conditions | Nurud Travels'],
        '/services' => ['search-title', 'Our Services | Flights, NIN/BVN, Passport, TIN | Nurud Travels'],
        '/services/nin-bvn-enrollment-uk' => ['search-title', 'NIN & BVN Enrolment for Nigerians in the UK | Nurud Travels'],
        '/services/nigerian-passport-renewal-london' => ['search-title', 'Nigerian Passport Renewal in London from the UK | Nurud Travels'],
        '/services/tin-registration-uk' => ['search-title', 'Tax ID (TIN) Registration for Nigerians in the UK | Nurud Travels'],
        '/services/book-now-pay-later-flights' => ['search-title', 'Book Now Pay Later Flights for Diaspora UK Travel | Nurud Travels'],
        '/services/travel-insurance-nigeria' => ['search-title', 'Travel Insurance for Nigerians & UK Travellers | Nurud Travels'],
        '/travel-guides' => ['search-title', 'Nigeria Travel Guides for UK Travellers | Nurud Travels'],
        '/travel-guides/nigeria-entry-visa-requirements-uk' => ['search-title', 'Nigeria Entry Visa Requirements for UK Citizens | Nurud Travels'],
        '/travel-guides/lagos-murtala-muhammed-airport-guide' => ['search-title', 'Lagos Murtala Muhammed Airport (LOS) Guide | Nurud Travels'],
        '/travel-guides/how-to-renew-nigerian-passport-from-uk' => ['search-title', 'How to Renew a Nigerian Passport from the UK | Nurud Travels'],
        '/travel-guides/nin-bvn-enrolment-centres-uk' => ['search-title', 'NIN & BVN Enrolment Centres in the UK | Nurud Travels'],
        '/travel-guides/best-time-to-fly-london-to-lagos' => ['search-title', 'Best Time to Fly London to Lagos for Cheap Fares | Nurud Travels'],
        '/travel-agent-woolwich-london' => ['search-title', 'Travel Agent in Woolwich, London | Nurud Travels'],
        '/destinations' => ['search-title', 'Travel Destinations | Flights from London to World Destinations | Nurud Travels'],
        '/destinations/italy' => ['search-title', 'Travel to Italy | Flights, Things to Do & Life in Italy | Nurud Travels'],
        '/destinations/spain' => ['search-title', 'Travel to Spain | Flights, Things to Do & Life in Spain | Nurud Travels'],
        '/destinations/thailand' => ['search-title', 'Travel to Thailand | Flights, Things to Do & Life in Thailand | Nurud Travels'],
        '/destinations/japan' => ['search-title', 'Travel to Japan | Flights, Things to Do & Life in Japan | Nurud Travels'],
        '/destinations/india' => ['search-title', 'Travel to India | Flights, Things to Do & Life in India | Nurud Travels'],
        '/destinations/greece' => ['search-title', 'Travel to Greece | Flights, Things to Do & Life in Greece | Nurud Travels'],
        '/destinations/australia' => ['search-title', 'Travel to Australia | Flights, Things to Do & Life in Australia | Nurud Travels'],
        '/destinations/nigeria' => ['search-title', 'Travel to Nigeria | Flights, Things to Do & Life in Nigeria | Nurud Travels'],
    ];

    foreach ($routes as $uri => [$needle, $expectedTitle]) {
        $this->get($uri)->assertOk($uri);
        $this->get($uri)->assertSee($expectedTitle, true, $uri);
        $this->get($uri)->assertDontSee('noindex', false, $uri);
    }
});

it('noindexes login-adjacent utility pages but keeps login and register indexable', function () {
    $this->get('/forgot-password')->assertOk();
    $this->get('/forgot-password')->assertSee('noindex');

    $this->get('/login')->assertOk();
    $this->get('/login')->assertDontSee('noindex');
});

it('has zero unsplash remote image URLs on the homepage', function () {
    $response = $this->get('/');
    $response->assertOk();
    $response->assertDontSee('images.unsplash.com', false);
});

it('generates a sitemap with static and seeded flight routes', function () {
    $path = public_path('sitemap.xml');
    @unlink($path);

    Artisan::call('sitemap:generate');

    $this->assertFileExists($path);
    $xml = File::get($path);

    $expectedPaths = [
        '/',
        '/login',
        '/services',
        '/travel-guides',
        '/travel-agent-woolwich-london',
        '/destinations',
        '/destinations/italy',
        '/destinations/spain',
        '/destinations/thailand',
        '/destinations/japan',
        '/destinations/india',
        '/destinations/greece',
        '/destinations/australia',
        '/destinations/nigeria',
    ];

    foreach ($expectedPaths as $uri) {
        $this->assertStringContainsString($uri, $xml, $uri);
    }

    // When seeded, seeded route slugs should appear.
    if (Schema::hasTable('flight_routes')) {
        $slugs = FlightRoute::where('is_indexable', true)->pluck('slug')->all();
        foreach ($slugs as $slug) {
            $this->assertStringContainsString('/flights/' . $slug, $xml, $slug);
        }
    }
});