@extends('layouts.front')

@section('seo-title', 'Travel Destinations | Flights from London to World Destinations | Nurud Travels')
@section('seo-description', 'Explore our travel destination guides — Italy, Spain, Thailand, Japan, India, Greece, Australia and Nigeria. Things to do, life on the ground and cheap flights from London with Nurud Travels.')

@section('content')
<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Destinations</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Trending Travel Destinations</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Country-by-country travel guides for popular destinations from London — what to see and do, everyday life on the ground, and why now is a great time to visit. Choose a country to explore flights, things to do and lodging tips.</p>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($countries as $country)
            <a href="{{ route('destinations.show', $country['slug']) }}" class="group block">
                <div class="relative rounded-2xl overflow-hidden aspect-[4/3]">
                    <img src="{{ asset($country['hero_image']) }}" alt="{{ $country['name'] }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-4">
                        <h2 class="font-bold text-white text-lg drop-shadow">{{ $country['name'] }}</h2>
                        <p class="text-white/70 text-xs">{{ $country['short'] }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</main>
@endsection