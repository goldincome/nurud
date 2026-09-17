@extends('layouts.front')

@section('seo-title', 'Lagos Murtala Muhammed Airport (LOS) Guide | Nurud Travels')
@section('seo-description', 'Your complete guide to Lagos Murtala Muhammed International Airport (LOS): terminals, arrival tips, currency exchange, SIM cards and getting into the city after your flight from the UK.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('guides.index') }}" class="hover:text-brand-red">Travel Guides</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Lagos Airport Guide</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Lagos Murtala Muhammed International Airport Guide</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">If you are flying the <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">London to Lagos route</a>, this is what to expect from touchdown at Murtala Muhammed International Airport (LOS) — Nigeria's busiest airport and the gateway to West Africa.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Terminals: know where you land</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">International flights arrive at the <strong>General Aviation Terminal (Terminal 1)</strong>, also home to the new international terminal used by several airlines. If you connect onward to a domestic Nigerian city, you leave the international terminal, clear arrivals, and re-enter the domestic terminal (MM2) for your onward flight — so leave at least 3 hours between international arrival and domestic departure.</p>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span>Domestic connections: Abuja (ABV), Kano, Port Harcourt, Enugu and more</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-circle-check text-brand-blue mt-0.5"></i><span>Shuttle buses and taxis connect the international and domestic terminals</span></li>
        </ul>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Arriving at LOS: step by step</h2>
        <ol class="space-y-3 text-gray-700 dark:text-slate-300 text-sm list-decimal list-inside">
            <li><strong class="text-brand-grayDark dark:text-white">Immigration</strong> — have your passport, visa approval and yellow fever card ready. Queues move faster in the early hours.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Baggage</strong> — collect checked bags, then <strong>check that your bags were not tampered with</strong> before leaving the carousel area.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Customs</strong> — declare goods as required. Nigerian customs are strict on phones, laptops and cash over the limit.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Money &amp; SIM</strong> — withdraw naira from bank ATMs in the arrivals hall, and buy a local SIM card from MTN, Airtel or Glo providers inside the terminal.</li>
        </ol>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Getting into the city</h2>
        <p class="text-gray-600 dark:text-slate-300 text-sm mb-3">Lagos traffic is unpredictable; the airport sits on the mainland (Ikeja), about 45 minutes to 2 hours from Victoria Island and Lekki depending on the time of day. Official airport taxi kiosks are the safest first choice. Book a trusted ride service or arrange collection with your host and <strong>never accept rides from unofficial touts at the exit</strong>.</p>
        <p class="text-gray-600 dark:text-slate-300 text-sm">Tip: land at quieter times — Monday–Thursday morning arrivals usually face lighter traffic than Friday or Sunday evenings.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Frequently asked</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Which terminal do British Airways and other UK flights use?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">UK direct services (British Airways, Virgin Atlantic, Air Peace) operate from the international terminal at LOS. Check your airline's confirmation, as terminal changes do happen.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Is the airport safe for solo travellers?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">The terminals are policed and generally safe, but remain alert in crowds, keep valuables hidden and use official or pre-arranged transport. Never leave bags unattended.</p>
        </details>
    </section>
</main>
@endsection