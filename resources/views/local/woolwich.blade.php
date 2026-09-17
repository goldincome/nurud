@extends('layouts.front')

@section('seo-title', 'Travel Agent in Woolwich, London | Nurud Travels')
@section('seo-description', 'A real travel agency based in Woolwich, London. Book flights to Nigeria, get your NIN/BVN and passport sorted, and talk to a human advisor. Visit us or contact us today.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Travel Agent Woolwich</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Your Travel Agent in Woolwich, London</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Nurud Travels is your local, family-run travel agency in Woolwich, South East London. We are the people you call when you need a trusted hand with flights home, identity documents and travel advice — without the airport-queue chaos.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Why locals in Woolwich choose Nurud Travels</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Advice on the best fares and <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">routes to Lagos</a>, <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-abuja-abv']) }}" class="text-brand-blue hover:text-brand-red underline">Abuja</a> and beyond</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Help with Nigerian passports, NIN/BVN enrolment and TIN registration</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Hand-holding on visas, insurance and group or family travel</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Flexible <a href="{{ route('services.bnpl') }}" class="text-brand-blue hover:text-brand-red underline">book-now-pay-later</a> plans for those big family trips</span></li>
        </ul>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">What we do best</h2>
        <p class="text-gray-600 dark:text-slate-300 text-sm">Woolwich has one of London's largest Nigerian communities, and we serve it every day. Moved house and lost your documents? Sorting a wedding or a naming ceremony back home? Travelling with children? We plan it with you, phone by phone — and we stay close while you travel, in case a flight changes and you need a friendly voice to sort it.</p>
        <p class="text-gray-600 dark:text-slate-300 text-sm mt-3">Serving customers across Greenwich, Thamesmead, Plumstead and the whole of South London — online at nurud.com, in your neighbourhood, and on the phone.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Get in touch</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">Prefer a face-to-face chat before booking? Contact our Woolwich team and we will arrange a time that suits you.</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('contact') }}" class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm">Contact the Woolwich Office</a>
            <a href="{{ url('/') }}" class="bg-transparent border border-brand-blue text-brand-blue hover:bg-brand-blue/5 py-3 px-6 rounded-lg font-bold text-sm transition-colors">Search a Flight</a>
        </div>
    </section>
</main>
@endsection