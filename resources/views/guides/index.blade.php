@extends('layouts.front')

@section('seo-title', 'Nigeria Travel Guides for UK Travellers | Nurud Travels')
@section('seo-description', 'Practical travel guides for flying from the UK to Nigeria: visa and entry requirements, Lagos airport tips, passport renewal from London, NIN/BVN enrolment and when to fly for the best fares.')

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Travel Guides</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Nigeria Travel Guides</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Everything UK-based travellers need before flying to Nigeria — from entry requirements and airport walkthroughs to passport renewal, identity documents and the best time to book your flight.</p>

    <div class="grid sm:grid-cols-2 gap-5 mb-8">
        <article class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-2"><a href="{{ route('guides.visa-requirements') }}" class="hover:text-brand-red">Nigeria Entry Visa Requirements for UK Citizens</a></h2>
            <p class="text-sm text-gray-600 dark:text-slate-300 mb-3">Visa on arrival, e-visa options, required documents and how long processing takes before your trip to Lagos or Abuja.</p>
            <a href="{{ route('guides.visa-requirements') }}" class="text-brand-blue hover:text-brand-red text-sm font-semibold">Read the guide &rarr;</a>
        </article>

        <article class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-2"><a href="{{ route('guides.lagos-airport') }}" class="hover:text-brand-red">Lagos Murtala Muhammed Airport Guide</a></h2>
            <p class="text-sm text-gray-600 dark:text-slate-300 mb-3">Arriving at LOS? Terminals, immigration, currency exchange, SIM cards and the best ways to get into the city.</p>
            <a href="{{ route('guides.lagos-airport') }}" class="text-brand-blue hover:text-brand-red text-sm font-semibold">Read the guide &rarr;</a>
        </article>

        <article class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-2"><a href="{{ route('guides.passport-renewal') }}" class="hover:text-brand-red">How to Renew a Nigerian Passport from the UK</a></h2>
            <p class="text-sm text-gray-600 dark:text-slate-300 mb-3">A step-by-step walkthrough of the Nigeria Immigration Service online application, biometrics and delivery to your UK address.</p>
            <a href="{{ route('guides.passport-renewal') }}" class="text-brand-blue hover:text-brand-red text-sm font-semibold">Read the guide &rarr;</a>
        </article>

        <article class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-2"><a href="{{ route('guides.nin-bvn-centres') }}" class="hover:text-brand-red">NIN &amp; BVN Enrolment Centres in the UK</a></h2>
            <p class="text-sm text-gray-600 dark:text-slate-300 mb-3">Where and how to enrol for your National Identity Number or link your BVN from London and other UK cities.</p>
            <a href="{{ route('guides.nin-bvn-centres') }}" class="text-brand-blue hover:text-brand-red text-sm font-semibold">Read the guide &rarr;</a>
        </article>

        <article class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-gray-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-2"><a href="{{ route('guides.best-time-to-fly') }}" class="hover:text-brand-red">Best Time to Fly London to Lagos</a></h2>
            <p class="text-sm text-gray-600 dark:text-slate-300 mb-3">When fares drop, when to avoid the peak, and how far in advance to book for the cheapest flights to Lagos.</p>
            <a href="{{ route('guides.best-time-to-fly') }}" class="text-brand-blue hover:text-brand-red text-sm font-semibold">Read the guide &rarr;</a>
        </article>
    </div>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6">
        <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-2">Popular flight routes</h2>
        <div class="flex flex-wrap gap-3 text-sm">
            <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="underline text-brand-blue hover:text-brand-red">London to Lagos</a>
            <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-abuja-abv']) }}" class="underline text-brand-blue hover:text-brand-red">London to Abuja</a>
            <a href="{{ route('flights.route.show', ['route_slug' => 'manchester-man-to-lagos-los']) }}" class="underline text-brand-blue hover:text-brand-red">Manchester to Lagos</a>
            <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-accra-acc']) }}" class="underline text-brand-blue hover:text-brand-red">London to Accra</a>
            <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-dubai-dxb']) }}" class="underline text-brand-blue hover:text-brand-red">London to Dubai</a>
            <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-johannesburg-jnb']) }}" class="underline text-brand-blue hover:text-brand-red">London to Johannesburg</a>
        </div>
    </section>
</main>
@endsection