@extends('layouts.front')

@section('seo-title', 'Book Now Pay Later Flights for Diaspora UK Travel | Nurud Travels')
@section('seo-description', 'Reserve your international flight today and pay in instalments. Flexible book-now-pay-later options for Nigerians in the UK travelling to Lagos, Abuja and worldwide.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('services') }}" class="hover:text-brand-red">Services</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Book Now, Pay Later</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Book Now, Pay Later Flights</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Your family does not stop waiting to see you because payday is next week. Secure your seats at today's fare and spread the cost over several instalments before you travel — the smart way millions of Nigerians abroad book flights home.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">How book-now-pay-later works</h2>
        <ol class="space-y-3 text-gray-700 dark:text-slate-300 text-sm list-decimal list-inside">
            <li><strong class="text-brand-grayDark dark:text-white">Search your flight</strong> — pick your route and travel dates on our site or tell us your plans.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Reserve your seat</strong> — a small deposit (or sometimes none) holds your ticket and locks the fare.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Pay in instalments</strong> — settle the balance in agreed stages before the payment deadline.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Fly</strong> — your ticket is confirmed and sent once the full balance is cleared and all travel documents are verified.</li>
        </ol>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Popular routes booked with flexible payments</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">London to Lagos</a> — the most-booked diaspora route</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-abuja-abv']) }}" class="text-brand-blue hover:text-brand-red underline">London to Abuja</a> — direct services available</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span><a href="{{ route('flights.route.show', ['route_slug' => 'manchester-man-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">Manchester to Lagos</a> — perfect for northern England customers</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span><a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-accra-acc']) }}" class="text-brand-blue hover:text-brand-red underline">London to Accra</a> and other West African routes</span></li>
        </ul>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Frequently asked questions</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Do I need a credit check to use pay later?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">No. Our instalment plan is based on your booking terms, not a UK credit check. You simply agree to the instalment schedule at the time of reservation.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">What happens if I miss a payment?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Contact us as soon as you know a payment will be late. We can often adjust the schedule, but the airline's own fare rules determine whether a missed deadline causes re-pricing or cancellation. Early communication protects your fare.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Is my fare locked from day one?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Yes — the fare shown at reservation is the fare you pay, provided your instalments are completed by the agreed deadline and the airline does not apply a fare change permitted by its rules.</p>
        </details>
    </section>

    <div class="flex flex-wrap gap-3">
        <a href="{{ url('/') }}" class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm">Search Your Flight</a>
        <a href="{{ route('contact') }}" class="bg-transparent border border-brand-blue text-brand-blue hover:bg-brand-blue/5 py-3 px-6 rounded-lg font-bold text-sm transition-colors">Discuss an Instalment Plan</a>
    </div>
</main>
@endsection