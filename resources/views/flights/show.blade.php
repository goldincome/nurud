@extends('layouts.front')

@section('seo-title', $page['meta']['title'])
@section('seo-description', $page['meta']['description'])
@section('seo-canonical', $page['meta']['canonical'])
@section('seo-robots', $page['meta']['robots'])

@section('head')
    <x-json-ld :schema="$page['schema']" />
@endsection

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('guides.index') }}" class="hover:text-brand-red">Travel Guides</a></li>
            <li><span class="mx-1">/</span></li>
            <li class="text-brand-grayLight dark:text-slate-500 capitalize">Flights {{ $page['route']['origin_city'] }} to {{ $page['route']['destination_city'] }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <header class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6 mb-8 border border-gray-100 dark:border-slate-700">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-brand-grayDark dark:text-white capitalize">
            Flights from {{ $page['route']['origin_city'] }} ({{ $page['route']['origin_code'] }}) to {{ $page['route']['destination_city'] }} ({{ $page['route']['destination_code'] }})
        </h1>
        <p class="mt-2 text-gray-600 dark:text-slate-300">
            Compare fares. {{ $page['route']['has_direct'] ? 'Direct flights available' : 'Connecting flights available' }} from
            <span class="font-bold text-emerald-600">£{{ $page['route']['lowest_fare'] }}</span>
            @if($page['route']['primary_airline'])
                with {{ $page['route']['primary_airline'] }}.
            @endif
        </p>
    </header>

    {{-- Quick search --}}
    <div class="mb-8">
        @include('common.front.booking-form')
    </div>

    {{-- Key stats --}}
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8" aria-label="Route overview">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg border border-gray-100 dark:border-slate-700">
            <span class="text-xs text-gray-500 dark:text-slate-400 uppercase font-semibold">Average Flight Time</span>
            <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $page['route']['duration'] ?? '—' }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg border border-gray-100 dark:border-slate-700">
            <span class="text-xs text-gray-500 dark:text-slate-400 uppercase font-semibold">Distance</span>
            <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $page['route']['distance'] ?? '—' }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg border border-gray-100 dark:border-slate-700">
            <span class="text-xs text-gray-500 dark:text-slate-400 uppercase font-semibold">Direct Flight Status</span>
            <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ $page['route']['has_direct'] ? 'Available' : 'Connecting Only' }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-lg border border-gray-100 dark:border-slate-700">
            <span class="text-xs text-gray-500 dark:text-slate-400 uppercase font-semibold">Lowest Recorded Fare</span>
            <p class="text-lg font-bold text-emerald-600 mt-1">£{{ $page['route']['lowest_fare'] }}</p>
        </div>
    </section>

@if(!empty($page['live_fares']))
    <section class="mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Today's Live Prices</h2>
        <p class="text-xs text-gray-500 dark:text-slate-400 mb-4">
            Fares updated {{ $page['search_data']['departureDate'] ?? '' }} → {{ $page['search_data']['returnDate'] ?? '' }} · Round trip · Economy · 1 adult
        </p>

        @foreach($page['live_fares'] as $fare)
        <div class="bg-white dark:bg-slate-800 rounded-lg p-5 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow mb-4">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex-1 flex flex-col justify-center">
                    @foreach(($fare['itineraries'] ?? []) as $index => $leg)
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 {{ $index > 0 ? 'mt-4 pt-4 border-t border-slate-100 dark:border-slate-700' : '' }}">
                        <div class="flex items-center gap-4 w-full md:w-1/4">
                            <img src="https://pics.avs.io/200/60/{{ $leg['airlineCode'] }}.png" class="h-6 object-contain" alt="{{ $leg['airlineName'] }}">
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400">
                                    {{ $page['search_data']['routeModel'] == 1 ? ($index === 0 ? 'Outbound' : 'Return') : 'Flight ' . ($index + 1) }}
                                </div>
                                <h3 class="font-bold text-sm text-brand-grayDark dark:text-white">{{ $leg['airlineName'] }}</h3>
                            </div>
                        </div>

                        <div class="flex flex-1 justify-between items-center text-center w-full gap-4 md:gap-0">
                            <div class="text-left min-w-[80px]">
                                <div class="text-lg font-bold text-brand-grayDark dark:text-white leading-tight">{{ $leg['depTime'] }}</div>
                                <div class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $leg['depCity'] }}</div>
                                <div class="text-[10px] text-brand-blue">{{ $leg['depDate'] }}</div>
                            </div>
                            <div class="flex flex-col items-center flex-1 px-4">
                                <div class="text-[10px] text-brand-blue mb-1">{{ $leg['duration'] }}</div>
                                <div class="w-full h-px bg-slate-300 relative flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 absolute left-0"></div>
                                    <i class="fas fa-plane text-slate-300 text-[10px] transform rotate-90"></i>
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 absolute right-0"></div>
                                </div>
                                <div class="text-[10px] font-medium text-brand-orange mt-1">{{ $leg['stops'] }}</div>
                            </div>
                            <div class="text-right min-w-[80px]">
                                <div class="text-lg font-bold text-brand-grayDark dark:text-white leading-tight">{{ $leg['arrTime'] }}</div>
                                <div class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $leg['arrCity'] }}</div>
                                <div class="text-[10px] text-brand-blue">{{ $leg['arrDate'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="w-full md:w-1/5 flex flex-row md:flex-col justify-between items-center md:items-center gap-2 md:pl-6 md:border-l border-slate-100 dark:border-slate-700 min-h-full">
                    <div class="text-right md:text-center mt-auto mb-auto w-full">
                        <div class="w-8 h-1.5 bg-slate-900 dark:bg-slate-300 rounded-full mb-2 mx-auto hidden md:block"></div>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white">
                            <span>{{ config('app.currency_symbol') }}</span>{{ $fare['price'] }}
                        </div>
                        <form action="{{ route('api.offer.verify') }}" method="POST" class="w-full mt-3">
                            @csrf
                            <input type="hidden" name="allOffer" value="{{ rawurlencode(json_encode($fare['all_offer'])) }}">
                            <button type="submit" class="bg-brand-blue hover:bg-brand-blueHover text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-lg shadow-brand-blue/20 w-full block">
                                Book Now
                            </button>
                        </form>
                        <div class="mt-3 text-[10px] text-slate-400 space-y-1">
                            @if(!empty($fare['bags']))<p><i class="fas fa-suitcase-rolling mr-1"></i>{{ $fare['bags'] }}</p>@endif
                            @if(!empty($fare['cabin_bag']))<p><i class="fas fa-briefcase mr-1"></i>{{ $fare['cabin_bag'] }}</p>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>

@elseif(!empty($page['airlines_operating']))
<section class="mb-8">
    <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Airlines Operating This Route</h2>
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-slate-700">
                <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-slate-300">
                    <th class="p-3">Airline</th>
                    <th class="p-3">Type</th>
                    <th class="p-3 text-right">From</th>
                </tr>
            </thead>
            <tbody>
            @foreach($page['airlines_operating'] as $airline)
                <tr class="border-t border-gray-100 dark:border-slate-700">
                    <td class="p-3 font-semibold text-brand-grayDark dark:text-white">{{ $airline['name'] }}</td>
                    <td class="p-3 text-gray-600 dark:text-slate-300">{{ $airline['flight_type'] }}</td>
                    <td class="p-3 text-right text-brand-blue dark:text-brand-blueLight font-semibold">£{{ $airline['min_price'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

    @if(!empty($page['seasonal_tips']))
    <section class="mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Seasonal Travel Tips</h2>
        <ul class="space-y-2 bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-xl p-6">
            @foreach($page['seasonal_tips'] as $tip)
                <li class="flex items-start gap-3 text-sm text-gray-700 dark:text-slate-300">
                    <i class="fas fa-circle-check text-brand-blue mt-0.5"></i>
                    <span>{{ $tip }}</span>
                </li>
            @endforeach
        </ul>
    </section>
    @endif

    @if(!empty($page['faqs']))
    <section class="mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Frequently Asked Questions</h2>
        @foreach($page['faqs'] as $faq)
            <details class="bg-white dark:bg-slate-800 p-4 rounded-lg border border-gray-100 dark:border-slate-700 mb-2">
                <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">{{ $faq['question'] }}</summary>
                <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm leading-relaxed">{{ $faq['answer'] }}</p>
            </details>
        @endforeach
    </section>
    @endif

    {{-- Related routes + sister services --}}
    <div class="grid md:grid-cols-2 gap-6">
        <section class="bg-indigo-50 dark:bg-slate-800/60 rounded-xl p-6">
            <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-3">Popular Routes from London</h2>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red">London to Lagos</a></li>
                <li><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-abuja-abv']) }}" class="text-brand-blue hover:text-brand-red">London to Abuja</a></li>
                <li><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-accra-acc']) }}" class="text-brand-blue hover:text-brand-red">London to Accra</a></li>
                <li><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-dubai-dxb']) }}" class="text-brand-blue hover:text-brand-red">London to Dubai</a></li>
                <li><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-johannesburg-jnb']) }}" class="text-brand-blue hover:text-brand-red">London to Johannesburg</a></li>
                <li><a href="{{ route('flights.route.show', ['route_slug' => 'manchester-man-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red">Manchester to Lagos</a></li>
            </ul>
        </section>

        <section class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-gray-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-2">Travelling to Nigeria? We help with more than flights.</h2>
            <div class="flex flex-wrap gap-3 text-sm mt-3">
                <a href="{{ route('services.nin-bvn') }}" class="underline text-brand-blue hover:text-brand-red">NIN/BVN Enrolment</a>
                <a href="{{ route('services.passport') }}" class="underline text-brand-blue hover:text-brand-red">Nigerian Passport Renewal</a>
                <a href="{{ route('services.tin') }}" class="underline text-brand-blue hover:text-brand-red">Tax ID (TIN) Registration</a>
                <a href="https://charltonvirtualoffice.com" class="underline text-brand-blue hover:text-brand-red" target="_blank" rel="noopener">UK Virtual Business Address</a>
            </div>
        </section>
    </div>
</main>
@endsection