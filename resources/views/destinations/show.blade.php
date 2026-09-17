@extends('layouts.front')

@php
    $seo = $country['seo'] ?? [];
    $slug = $country['slug'];
@endphp

@section('seo-title', $seo['title'] ?? 'Travel to ' . $country['name'] . ' | Nurud Travels')
@section('seo-description', $seo['description'] ?? '')
@section('seo-canonical', route('destinations.show', $slug))

@section('head')
    @php
        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Destinations', 'item' => route('destinations.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $country['name'], 'item' => route('destinations.show', $slug)],
                    ],
                ],
                [
                    '@type' => 'TouristDestination',
                    'name' => $country['name'],
                    'description' => $country['short'],
                    'url' => route('destinations.show', $slug),
                ],
                [
                    '@type' => 'FAQPage',
                    'mainEntity' => array_map(fn ($faq) => [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                    ], $country['faqs'] ?? []),
                ],
            ],
        ];
    @endphp
    <x-json-ld :schema="$schema" />
@endsection

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('destinations.index') }}" class="hover:text-brand-red">Destinations</a></li>
            <li><span class="mx-1">/</span></li>
            <li>{{ $country['name'] }}</li>
        </ol>
    </nav>

    <header class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">{{ $country['h1'] }}</h1>
        <div class="relative rounded-2xl overflow-hidden aspect-[21/9] mb-5">
            <img src="{{ asset($country['hero_image']) }}" alt="{{ $country['name'] }}" class="w-full h-full object-cover" loading="eager">
        </div>
        <p class="text-gray-600 dark:text-slate-300 text-lg leading-relaxed">{{ $country['short'] }}</p>
    </header>

    {{-- Flight search --}}
    <section class="mb-10">
        @include('common.front.country-flight-search', ['country' => $country])
    </section>

    {{-- Why travel --}}
    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Why you should travel to {{ $country['name'] }}</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            @foreach($country['why'] as $reason)
                <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span>{{ $reason }}</span></li>
            @endforeach
        </ul>
    </section>

    {{-- Where to go --}}
    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Where to go &amp; things to do</h2>
        <p class="text-sm text-gray-600 dark:text-slate-300 mb-4">The places worth your time when you fly out to {{ $country['name'] }}.</p>
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach($country['where_to_go'] as $place)
                <div class="bg-brand-bluePale/40 dark:bg-slate-700/40 rounded-xl p-4">
                    <h3 class="font-bold text-brand-grayDark dark:text-white mb-1">{{ $place['name'] }}</h3>
                    <p class="text-sm text-gray-600 dark:text-slate-300">{{ $place['note'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Life in the country --}}
    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Life in {{ $country['name'] }}</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            @foreach($country['life'] as $insight)
                <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span>{{ $insight }}</span></li>
            @endforeach
        </ul>
    </section>

    {{-- Related flight routes --}}
    @if(!empty($country['route_slugs']))
    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-3">Popular flights</h2>
        <ul class="space-y-2 text-sm">
            @foreach($country['route_slugs'] as $routeSlug)
                <li>
                    <a href="{{ route('flights.route.show', ['route_slug' => $routeSlug]) }}" class="text-brand-blue hover:text-brand-red underline">
                        Flights from London to {{ \Illuminate\Support\Str::of($routeSlug)->after('-to-')->replace('-', ' ')->title() }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
    @endif

    {{-- FAQs --}}
    @if(!empty($country['faqs']))
    <section class="mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Frequently asked questions</h2>
        @foreach($country['faqs'] as $faq)
            <details class="bg-white dark:bg-slate-800 p-4 rounded-lg border border-gray-100 dark:border-slate-700 mb-2">
                <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">{{ $faq['question'] }}</summary>
                <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm leading-relaxed">{{ $faq['answer'] }}</p>
            </details>
        @endforeach
    </section>
    @endif

    {{-- Explore other destinations --}}
    <section class="flex flex-wrap gap-3">
        <a href="{{ route('destinations.index') }}" class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm">Explore All Destinations</a>
        <a href="{{ route('contact') }}" class="bg-transparent border border-brand-blue text-brand-blue hover:bg-brand-blue/5 py-3 px-6 rounded-lg font-bold text-sm transition-colors">Ask About This Trip</a>
    </section>
</main>
@endsection