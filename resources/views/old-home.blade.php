@extends('layouts.front')

@section('content')
    <main>
        {{-- ============================================================ --}}
        {{-- SERVICE TABS BAR - Right below main navigation --}}
        {{-- ============================================================ --}}
        <div class="service-tabs-bar">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap justify-center gap-2 sm:gap-3 py-3">
                    <a href="https://charltonvirtualoffice.com" target="_blank"
                        class="service-tab service-tab--flights active" data-tab="flights">
                        <i class="fas fa-id-card"></i>
                        <span>Get Your UK Virtual Address</span>
                    </a>
                    <a href="https://ninuk.co.uk" target="_blank" class="service-tab service-tab--nin">
                        <i class="fas fa-id-card"></i>
                        <span>Enroll For Your NIN/BVN</span>
                    </a>
                    <a href="https://ninuk.co.uk/DEACIL-professionals/process-nigerian-international-passport"
                        target="_blank" class="service-tab service-tab--passport">
                        <i class="fas fa-passport"></i>
                        <span>Passport Application Assistance</span>
                    </a>
                    <a href="https://ninuk.co.uk/DEACIL-professionals/nigerian-tax-identification-number-tin"
                        target="_blank" class="service-tab service-tab--tin">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Get Your Tax ID Number (TIN)</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- HERO SECTION - Deep blue gradient with floating city images --}}
        {{-- ============================================================ --}}
        <section class="hero-section relative overflow-hidden">
            {{-- Blue gradient background --}}
            <div class="absolute inset-0 hero-gradient"></div>
            {{-- Decorative floating city images --}}
            <div class="hero-city-images hidden lg:block">
                <div class="hero-city-img hero-city-1">
                    <img src="{{ asset("images/abuja-city.jpg") }}?q=80&w=600&auto=format&fit=crop" alt="City"
                        class="w-full h-full object-cover rounded-2xl">
                </div>
                <div class="hero-city-img hero-city-2">
                    <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=600&auto=format&fit=crop"
                        alt="City" class="w-full h-full object-cover rounded-2xl">
                </div>
                <div class="hero-city-img hero-city-3">
                    <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?q=80&w=600&auto=format&fit=crop"
                        alt="City" class="w-full h-full object-cover rounded-2xl">
                </div>
            </div>

            <div class="relative z-10 container mx-auto px-4 pt-10 pb-6 md:pt-14 md:pb-10 text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white leading-tight mb-3 drop-shadow-lg">
                    Get the best flight deals ever.</h1>
                <p class="text-base sm:text-lg text-white/80 mb-0 max-w-2xl mx-auto">
                    Discover and book flights to your dream destinations with ease.
                </p>
            </div>

            {{-- Booking form directly inside hero, no gap --}}
            <div class="relative z-20 pb-6 md:pb-10">
                @include('common.front.error-and-message')
                @include('common.front.booking-form')
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- TRAVEL DEALS UNDER $1,006 - Horizontal scrollable cards --}}
        {{-- ============================================================ --}}
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-brand-grayDark dark:text-white">
                    Travel deals under <span class="text-brand-red">£1,006</span>
                </h2>

            </div>
            <div class="deals-scroll-wrapper relative">
                <div class="deals-scroll flex gap-4 overflow-x-auto pb-4 snap-x snap-mandatory" id="deals-scroll">
                    @php
                        $deals = [
                            ['city' => 'Lagos', 'country' => 'Nigeria', 'price' => 463, 'img' => asset("images/lagos-city.jpg") . '?q=80&w=600&auto=format&fit=crop'],
                            ['city' => 'Abuja', 'country' => 'Nigeria', 'price' => 535, 'img' => asset("images/abuja-city.jpg") . '?q=80&w=600&auto=format&fit=crop'],
                            ['city' => 'Berlin', 'country' => 'Germany', 'price' => 326, 'img' => 'https://images.unsplash.com/photo-1560969184-10fe8719e047?q=80&w=600&auto=format&fit=crop'],
                            ['city' => 'Gatún', 'country' => 'Panama', 'price' => 486, 'img' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=600&auto=format&fit=crop'],
                            ['city' => 'London', 'country' => 'UK', 'price' => 450, 'img' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=600&auto=format&fit=crop'],
                            ['city' => 'Dubai', 'country' => 'UAE', 'price' => 720, 'img' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=600&auto=format&fit=crop'],
                        ];
                    @endphp
                    @foreach($deals as $deal)
                        <div class="deal-card flex-shrink-0 w-60 sm:w-64 snap-start cursor-pointer group">
                            <div class="relative rounded-xl overflow-hidden h-40 mb-3">
                                <img src="{{ $deal['img'] }}" alt="{{ $deal['city'] }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            </div>
                            <h3 class="font-bold text-brand-grayDark dark:text-white text-sm">{{ $deal['city'] }}</h3>
                            <p class="text-xs text-brand-grayLight dark:text-slate-400">{{ $deal['country'] }}</p>
                            <p class="text-sm font-bold text-brand-blue mt-1">from £{{ $deal['price'] }}</p>
                        </div>
                    @endforeach
                </div>
                {{-- Scroll Buttons --}}
                <button onclick="document.getElementById('deals-scroll').scrollBy({left:-280,behavior:'smooth'})"
                    class="deals-scroll-btn absolute left-0 top-16 -translate-y-1/2 -translate-x-3 bg-white dark:bg-slate-700 shadow-lg rounded-full w-10 h-10 flex items-center justify-center text-brand-grayDark dark:text-white hover:bg-slate-100 dark:hover:bg-slate-600 z-10 hidden md:flex">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="document.getElementById('deals-scroll').scrollBy({left:280,behavior:'smooth'})"
                    class="deals-scroll-btn absolute right-0 top-16 -translate-y-1/2 translate-x-3 bg-white dark:bg-slate-700 shadow-lg rounded-full w-10 h-10 flex items-center justify-center text-brand-grayDark dark:text-white hover:bg-slate-100 dark:hover:bg-slate-600 z-10 hidden md:flex">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- FOR TRAVEL PROS - Feature cards with icons --}}
        {{-- ============================================================ --}}
        <section class="bg-brand-bluePale/40 dark:bg-slate-800/50 py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-xl sm:text-2xl font-bold mb-8 text-brand-grayDark dark:text-white">For travel pros</h2>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    {{-- Feature 1: Explore --}}
                    <div class="travel-pro-card group">
                        <div class="travel-pro-icon bg-brand-blue/10">
                            <i class="fas fa-globe-americas text-brand-blue text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-brand-grayDark dark:text-white mt-4 mb-1">Explore
                        </h3>
                        <p class="text-xs sm:text-sm text-brand-gray dark:text-slate-400">Find the best destinations for
                            your budget</p>
                    </div>
                    {{-- Feature 2: Trips --}}
                    <div class="travel-pro-card group">
                        <div class="travel-pro-icon bg-brand-red/10">
                            <i class="fas fa-suitcase text-brand-red text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-brand-grayDark dark:text-white mt-4 mb-1">Trips</h3>
                        <p class="text-xs sm:text-sm text-brand-gray dark:text-slate-400">Manage all your upcoming trips in
                            one place</p>
                    </div>
                    {{-- Feature 3: Price Alerts --}}
                    <div class="travel-pro-card group">
                        <div class="travel-pro-icon bg-green-500/10">
                            <i class="fas fa-bell text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-brand-grayDark dark:text-white mt-4 mb-1">Price
                            Alerts</h3>
                        <p class="text-xs sm:text-sm text-brand-gray dark:text-slate-400">Get notified when prices drop for
                            your routes</p>
                    </div>
                    {{-- Feature 4: Flight Tracker --}}
                    <div class="travel-pro-card group">
                        <div class="travel-pro-icon bg-brand-blueLight/10">
                            <i class="fas fa-plane text-brand-blueLight text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-sm sm:text-base text-brand-grayDark dark:text-white mt-4 mb-1">Flight
                            Tracker</h3>
                        <p class="text-xs sm:text-sm text-brand-gray dark:text-slate-400">Real-time updates on your flight
                            details</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- TRENDING CITIES - Grid of destination photos --}}
        {{-- ============================================================ --}}
        <section class="trending-section py-14">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl font-bold text-brand-grayDark dark:text-white mb-2">Trending cities</h2>
                    <p class="text-brand-gray dark:text-slate-400 text-sm">Discover the most searched destinations worldwide
                    </p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    @php
                        $cities = [
                            ['name' => 'Paris', 'country' => 'France', 'img' => asset("images/paris-city.jpg") . '?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Bangkok', 'country' => 'Thailand', 'img' => 'https://images.unsplash.com/photo-1508009603885-50cf7c579365?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'New York', 'country' => 'USA', 'img' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'London', 'country' => 'UK', 'img' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Sydney', 'country' => 'Australia', 'img' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Dubai', 'country' => 'UAE', 'img' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Tokyo', 'country' => 'Japan', 'img' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Rome', 'country' => 'Italy', 'img' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Barcelona', 'country' => 'Spain', 'img' => 'https://images.unsplash.com/photo-1583422409516-2895a77efded?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Istanbul', 'country' => 'Turkey', 'img' => 'https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Bali', 'country' => 'Indonesia', 'img' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Lagos', 'country' => 'Nigeria', 'img' => 'https://images.unsplash.com/photo-1618828665011-0abd973f7bb8?q=80&w=600&auto=format&fit=crop'],
                        ];
                    @endphp
                    @foreach($cities as $city)
                        <a href="#" class="trending-city-card group">
                            <div class="relative rounded-xl overflow-hidden aspect-[4/3]">
                                <img src="{{ $city['img'] }}" alt="{{ $city['name'] }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                    loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-3 sm:p-4">
                                    <h3 class="font-bold text-white text-sm sm:text-base drop-shadow">{{ $city['name'] }}</h3>
                                    <p class="text-white/70 text-xs">{{ $city['country'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- TRENDING COUNTRIES - Grid with bigger cards --}}
        {{-- ============================================================ --}}
        <section class="bg-brand-bluePale/40 dark:bg-slate-800/50 py-14">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl font-bold text-brand-grayDark dark:text-white mb-2">Trending countries
                    </h2>
                    <p class="text-brand-gray dark:text-slate-400 text-sm">The most searched for countries in the world</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                    @php
                        $countries = [
                            ['name' => 'Italy', 'flights' => '2.5k+ flights', 'img' => 'https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Spain', 'flights' => '1.8k+ flights', 'img' => asset("images/spain-city.jpg") . '?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Thailand', 'flights' => '1.2k+ flights', 'img' => asset("images/thailand-city.jpg") . '?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Japan', 'flights' => '1.4k+ flights', 'img' => 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'India', 'flights' => '3.1k+ flights', 'img' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Greece', 'flights' => '900+ flights', 'img' => 'https://images.unsplash.com/photo-1533104816931-20fa691ff6ca?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Australia', 'flights' => '1.1k+ flights', 'img' => 'https://images.unsplash.com/photo-1523482580672-f109ba8cb9be?q=80&w=600&auto=format&fit=crop'],
                            ['name' => 'Nigeria', 'flights' => '800+ flights', 'img' => 'https://images.unsplash.com/photo-1618828665011-0abd973f7bb8?q=80&w=600&auto=format&fit=crop'],
                        ];
                    @endphp
                    @foreach($countries as $country)
                        <a href="#" class="trending-country-card group">
                            <div class="relative rounded-xl overflow-hidden aspect-[4/3]">
                                <img src="{{ $country['img'] }}" alt="{{ $country['name'] }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                    loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 p-3 sm:p-4">
                                    <h3 class="font-bold text-white text-base sm:text-lg drop-shadow">{{ $country['name'] }}
                                    </h3>
                                    <p class="text-white/70 text-xs flex items-center"><i
                                            class="fas fa-plane-departure mr-1 text-[10px]"></i>{{ $country['flights'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- FLIGHT DEALS BY DESTINATION --}}
        {{-- ============================================================ --}}
        <section class="py-14">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl font-bold text-brand-grayDark dark:text-white mb-2">Flight deals by
                        destination</h2>
                    <p class="text-brand-gray dark:text-slate-400 text-sm">Find and compare the best flight deals</p>
                    <p class="text-brand-grayLight dark:text-slate-500 text-xs mt-1">Compare flight prices from over 900
                        airlines and travel sites, including KAYAK, Expedia, Priceline, and more.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
                    @php
                        $flightDeals = [
                            ['region' => 'Africa', 'routes' => ['Lagos', 'Accra', 'Nairobi', 'Johannesburg', 'Cairo', 'Addis Ababa']],
                            ['region' => 'North America', 'routes' => ['New York', 'Los Angeles', 'Chicago', 'Miami', 'San Francisco', 'Toronto']],
                            ['region' => 'Europe', 'routes' => ['London', 'Paris', 'Rome', 'Barcelona', 'Amsterdam', 'Berlin']],
                            ['region' => 'Asia', 'routes' => ['Bangkok', 'Tokyo', 'Singapore', 'Dubai', 'Bali', 'Mumbai']],
                        ];
                    @endphp
                    @foreach($flightDeals as $region)
                        <div class="flight-deal-region">
                            <h3 class="font-bold text-brand-grayDark dark:text-white text-base mb-3">{{ $region['region'] }}
                            </h3>
                            <ul class="space-y-2">
                                @foreach($region['routes'] as $route)
                                    <li>
                                        <a href="#"
                                            class="text-sm text-brand-blue hover:text-brand-red dark:text-brand-blueLight dark:hover:text-brand-redLight transition-colors flex items-center gap-2">
                                            <i class="fas fa-plane text-[10px] text-brand-grayLight"></i>
                                            Flights to {{ $route }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- HOW TO FIND CHEAP FLIGHTS --}}
        {{-- ============================================================ --}}
        <section class="how-it-works-section py-14">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">How to find cheap flight deals with Nurud
                        Travels</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="how-step text-center">
                        <div class="how-step-icon mx-auto mb-4">
                            <i class="fas fa-search text-3xl text-white"></i>
                        </div>
                        <h3 class="font-bold text-white text-lg mb-2">1. Search</h3>
                        <p class="text-white/70 text-sm">Enter your destination, travel dates, and number of passengers to
                            compare prices from hundreds of travel sites.</p>
                    </div>
                    <div class="how-step text-center">
                        <div class="how-step-icon mx-auto mb-4">
                            <i class="fas fa-chart-line text-3xl text-white"></i>
                        </div>
                        <h3 class="font-bold text-white text-lg mb-2">2. Compare</h3>
                        <p class="text-white/70 text-sm">We search across all major airlines and booking sites to show you
                            the cheapest options available.</p>
                    </div>
                    <div class="how-step text-center">
                        <div class="how-step-icon mx-auto mb-4">
                            <i class="fas fa-ticket-alt text-3xl text-white"></i>
                        </div>
                        <h3 class="font-bold text-white text-lg mb-2">3. Book</h3>
                        <p class="text-white/70 text-sm">Found the perfect deal? Book directly through our platform and get
                            your e-ticket instantly.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- CTA BANNER --}}
        {{-- ============================================================ --}}
        <section class="py-16 bg-white dark:bg-slate-900">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-brand-grayDark dark:text-white mb-4">Ready for your next
                    adventure?</h2>
                <p class="text-brand-gray dark:text-slate-400 text-base mb-8 max-w-xl mx-auto">Sign up for price alerts and
                    never miss a deal on flights to your favourite destinations.</p>
                <form id="subscribe-form"
                    class="flex flex-col sm:flex-row gap-3 justify-center max-w-md mx-auto items-start">
                    @csrf
                    <div class="flex-1 w-full relative">
                        <input type="email" id="subscribe-email" name="email" placeholder="Enter your email address"
                            required
                            class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-blue text-sm">
                        <div id="subscribe-message" class="absolute -bottom-6 left-0 text-xs w-full text-left"></div>
                    </div>
                    <button type="submit" id="subscribe-btn"
                        class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm whitespace-nowrap disabled:opacity-50">
                        Get Alerts
                    </button>
                </form>
            </div>
        </section>
    </main>
@endsection

@section('css')
    <style>
        /* ====================== HERO SECTION ====================== */
        .hero-section {
            min-height: auto;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #151F55 0%, #2E3B82 40%, #4A5BA0 80%, #5B6CB5 100%);
        }

        .hero-city-images {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .hero-city-img {
            position: absolute;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            opacity: 0.5;
        }

        .hero-city-1 {
            width: 200px;
            height: 140px;
            top: 30%;
            right: 5%;
            transform: rotate(6deg);
            animation: float-city 8s ease-in-out infinite;
        }

        .hero-city-2 {
            width: 160px;
            height: 120px;
            top: 15%;
            right: 18%;
            transform: rotate(-4deg);
            animation: float-city 10s ease-in-out infinite 2s;
        }

        .hero-city-3 {
            width: 180px;
            height: 130px;
            top: 50%;
            right: 12%;
            transform: rotate(2deg);
            animation: float-city 9s ease-in-out infinite 4s;
        }

        @keyframes float-city {

            0%,
            100% {
                transform: translateY(0) rotate(var(--rot, 0deg));
            }

            50% {
                transform: translateY(-12px) rotate(var(--rot, 0deg));
            }
        }

        .hero-city-1 {
            --rot: 6deg;
        }

        .hero-city-2 {
            --rot: -4deg;
        }

        .hero-city-3 {
            --rot: 2deg;
        }

        /* ====================== SERVICE TABS BAR ====================== */
        .service-tabs-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .service-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: white;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .service-tab i,
        .service-tab span {
            position: relative;
            z-index: 1;
        }

        .service-tab i {
            font-size: 14px;
        }

        .service-tab::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: inherit;
        }

        .service-tab:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);

        }

        .service-tab:hover::before {
            opacity: 1;
        }

        /* --- Flights: Vibrant Blue --- */
        .service-tab--flights {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.3);
        }

        .service-tab--flights::before {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }

        .service-tab--flights:hover {
            box-shadow: 0 6px 24px rgba(37, 99, 235, 0.5);
        }

        .service-tab--flights.active {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.4), 0 6px 24px rgba(37, 99, 235, 0.5);
        }

        /* --- NIN/BVN: Emerald Green --- */
        .service-tab--nin {
            background: linear-gradient(135deg, #059669, #10b981);
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 2px 12px rgba(5, 150, 105, 0.3);
        }

        .service-tab--nin::before {
            background: linear-gradient(135deg, #047857, #059669);
        }

        .service-tab--nin:hover {
            box-shadow: 0 6px 24px rgba(5, 150, 105, 0.5);
        }

        /* --- Passport: Warm Amber/Orange --- */
        .service-tab--passport {
            background: linear-gradient(135deg, #d97706, #f59e0b);
            border-color: rgba(245, 158, 11, 0.3);
            box-shadow: 0 2px 12px rgba(217, 119, 6, 0.3);
        }

        .service-tab--passport::before {
            background: linear-gradient(135deg, #b45309, #d97706);
        }

        .service-tab--passport:hover {
            box-shadow: 0 6px 24px rgba(217, 119, 6, 0.5);
        }

        /* --- TIN: Rich Purple --- */
        .service-tab--tin {
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
            border-color: rgba(167, 139, 250, 0.3);
            box-shadow: 0 2px 12px rgba(124, 58, 237, 0.3);
        }

        .service-tab--tin::before {
            background: linear-gradient(135deg, #6d28d9, #7c3aed);
        }

        .service-tab--tin:hover {
            box-shadow: 0 6px 24px rgba(124, 58, 237, 0.5);
        }

        /* ====================== DEALS SECTION ====================== */
        .deals-scroll::-webkit-scrollbar {
            display: none;
        }

        .deals-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .deal-card:hover {
            transform: translateY(-4px);
        }

        .deal-card {
            transition: transform 0.3s ease;
        }

        /* ====================== TRAVEL PRO CARDS ====================== */
        .travel-pro-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .travel-pro-card {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .travel-pro-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(46, 59, 130, 0.12);
        }

        .travel-pro-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ====================== TRENDING CARDS ====================== */
        .trending-city-card,
        .trending-country-card {
            display: block;
            transition: transform 0.3s ease;
        }

        .trending-city-card:hover,
        .trending-country-card:hover {
            transform: translateY(-4px);
        }

        /* ====================== HOW IT WORKS ====================== */
        .how-it-works-section {
            background: linear-gradient(135deg, #151F55 0%, #2E3B82 60%, #3A4990 100%);
        }

        .how-step-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        /* ====================== FLIGHT DEAL REGION ====================== */
        .flight-deal-region {
            padding: 1.5rem;
            background: rgba(46, 59, 130, 0.04);
            border-radius: 1rem;
            border: 1px solid rgba(46, 59, 130, 0.08);
        }

        .dark .flight-deal-region {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.06);
        }

        /* ====================== RESPONSIVE ====================== */
        @media (max-width: 640px) {
            .service-tab {
                padding: 8px 12px;
                font-size: 11px;
                border-radius: 10px;
                gap: 6px;
            }

            .service-tab i {
                font-size: 12px;
            }

            .service-tabs-bar .flex {
                gap: 6px;
            }

            .hero-section {
                min-height: auto;
            }
        }

        @media (max-width: 480px) {
            .service-tab {
                padding: 7px 10px;
                font-size: 10px;
            }

            .service-tab span {
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Service tab switching (visual only for Flights button)
            const serviceTabs = document.querySelectorAll('.service-tab');
            serviceTabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    serviceTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Newsletter Subscription
            const subscribeForm = document.getElementById('subscribe-form');
            if (subscribeForm) {
                subscribeForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const email = document.getElementById('subscribe-email').value;
                    const btn = document.getElementById('subscribe-btn');
                    const messageDiv = document.getElementById('subscribe-message');

                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Subscribing...';
                    messageDiv.textContent = '';

                    fetch('{{ route('subscribe') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: email })
                    })
                        .then(response => response.json())
                        .then(data => {
                            btn.disabled = false;
                            btn.textContent = 'Get Alerts';

                            if (data.success) {
                                messageDiv.textContent = data.message;
                                messageDiv.className = 'absolute -bottom-6 left-0 text-xs w-full text-left text-green-600';
                                document.getElementById('subscribe-email').value = '';
                            } else if (data.errors && data.errors.email) {
                                messageDiv.textContent = data.errors.email[0];
                                messageDiv.className = 'absolute -bottom-6 left-0 text-xs w-full text-left text-brand-red';
                            } else {
                                messageDiv.textContent = data.message || 'An error occurred. Please try again.';
                                messageDiv.className = 'absolute -bottom-6 left-0 text-xs w-full text-left text-brand-red';
                            }

                            setTimeout(() => {
                                messageDiv.textContent = '';
                            }, 5000);
                        })
                        .catch(error => {
                            btn.disabled = false;
                            btn.textContent = 'Get Alerts';
                            messageDiv.textContent = 'An error occurred. Please try again.';
                            messageDiv.className = 'absolute -bottom-6 left-0 text-xs w-full text-left text-brand-red';

                            setTimeout(() => {
                                messageDiv.textContent = '';
                            }, 5000);
                        });
                });
            }
        });
    </script>
@endsection