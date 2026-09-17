@extends('layouts.front')

@section('seo-title', 'Travel Insurance for Nigerians & UK Travellers | Nurud Travels')
@section('seo-description', 'Compare travel insurance for flights to Nigeria and worldwide. Medical cover, baggage protection, cancellation cover and trip support for the diaspora and UK travellers.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('services') }}" class="hover:text-brand-red">Services</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Travel Insurance</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Travel Insurance for Trips to Nigeria &amp; Worldwide</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">One medical emergency or missed connection can wipe out months of saving. Travel insurance protects your trip — covering medical treatment, cancellation, lost baggage and delays — so you can focus on the family waiting at the airport in Lagos.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">What good flight insurance covers</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span><strong class="text-brand-grayDark dark:text-white">Emergency medical cover</strong> — treatment costs abroad, including medical evacuation if needed</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span><strong class="text-brand-grayDark dark:text-white">Cancellation cover</strong> — reimbursement for non-refundable tickets if you must cancel for a covered reason</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span><strong class="text-brand-grayDark dark:text-white">Baggage protection</strong> — lost, delayed or damaged luggage</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span><strong class="text-brand-grayDark dark:text-white">Travel delay</strong> — costs when flights are delayed or diverted</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span><strong class="text-brand-grayDark dark:text-white">Personal liability &amp; legal help</strong> — 24/7 assistance line</span></li>
        </ul>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Why travelling to Nigeria makes insurance essential</h2>
        <p class="text-gray-600 dark:text-slate-300 text-sm mb-3">Long-haul flights carry a higher risk of deep-vein thrombosis, and medical costs in Nigeria are usually paid out of pocket before treatment begins. A UK or international policy with emergency cover means your family can focus on your recovery, not on raising funds. Policies also cover travel between multiple Nigerian cities by road, which many travellers underestimate.</p>
        <p class="text-gray-600 dark:text-slate-300 text-sm">Wherever you are heading — <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">Lagos</a>, <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-abuja-abv']) }}" class="text-brand-blue hover:text-brand-red underline">Abuja</a>, <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-dubai-dxb']) }}" class="text-brand-blue hover:text-brand-red underline">Dubai</a> or beyond — we help you select cover that matches your itinerary.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Questions travellers ask about insurance</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Can I buy insurance after booking my flight?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Yes, but buy it as early as possible. Cover for cancellation and medical conditions generally starts from the policy purchase date, so the earlier you buy, the more of your trip is protected.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Does my Ghana or Nigeria trip need special cover?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Standard international policies cover most African destinations. Always check the insurer's country list and the pre-existing conditions clause. We can compare options for you.</p>
        </details>
        <details>
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">What should I do if I need to claim abroad?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Call the 24/7 emergency line on your policy first, keep every receipt, and report lost baggage at the airport before you leave the arrivals hall. We help you prepare the paperwork.</p>
        </details>
    </section>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('contact') }}" class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm">Compare Insurance Options</a>
        <a href="{{ url('/') }}" class="bg-transparent border border-brand-blue text-brand-blue hover:bg-brand-blue/5 py-3 px-6 rounded-lg font-bold text-sm transition-colors">Book Your Flights First</a>
    </div>
</main>
@endsection