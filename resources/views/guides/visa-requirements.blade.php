@extends('layouts.front')

@section('seo-title', 'Nigeria Entry Visa Requirements for UK Citizens | Nurud Travels')
@section('seo-description', 'Do UK citizens need a visa for Nigeria? Learn about visa on arrival, e-visa, documents required and how to apply before your flight to Lagos, Abuja or other Nigerian cities.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('guides.index') }}" class="hover:text-brand-red">Travel Guides</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Nigeria Visa Requirements</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Nigeria Entry Visa Requirements for UK Citizens in 2026</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Every non-Nigerian visitor, including British citizens, needs a valid passport and either a visa or a recognised visa waiver to enter Nigeria. Getting the paperwork right before you fly is the difference between a smooth arrival and being held at immigration in Lagos.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Do UK citizens need a visa for Nigeria?</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">Yes. British passport holders do not enjoy visa-free entry to Nigeria. However, most short visits can be arranged through the <strong>Nigeria Visa on Arrival (VoA)</strong> process or the standard e-visa application, depending on the purpose of your trip and your nationality background.</p>
        <p class="text-gray-600 dark:text-slate-300">VoA is a pre-approved clearance issued by the Nigeria Immigration Service before you travel — it is not a visa obtained at the airport counter as you might imagine. You must apply in advance and receive approval before boarding your flight. Nigerians intending to fly home should always confirm they are travelling with their own nationality passport, as entry rules differ by country.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Key requirements checklist</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Passport valid for at least 6 months with blank pages</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Visa on Arrival approval or approved e-visa for your nationality</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Invitation letter from your host or business contact in Nigeria where required</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Return or onward ticket proof</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Evidence of accommodation and sufficient funds for your stay</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Yellow fever vaccination certificate — requested on arrival</span></li>
        </ul>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Plan around your flight</h2>
        <p class="text-gray-600 dark:text-slate-300 text-sm mb-3">Visa approval letters are usually tied to your travel dates, so finalise your itinerary before applying. Most travellers fly into <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">Lagos (LOS)</a> or <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-abuja-abv']) }}" class="text-brand-blue hover:text-brand-red underline">Abuja (ABV)</a>. If you are visiting Accra as part of a West Africa trip, check Ghanaian entry rules separately — our <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-accra-acc']) }}" class="text-brand-blue hover:text-brand-red underline">London to Accra route page</a> has details.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Questions about Nigerian visas</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">How long before my flight should I apply?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Apply 3–6 weeks before departure. VoA and e-visa processing times vary, and corrections cause delays. Leave a buffer: visa issues are the single most common reason travellers miss flights to Nigeria.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Do Nigerian-born UK citizens need a visa?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">If you hold Nigerian nationality and a Nigerian passport, you do not need a visa — you enter as a citizen. UK-born children of Nigerians generally travel on their foreign passport and may need a visa or a special arrangement; confirm your position with the consulate before booking.</p>
        </details>
        <details>
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">What happens if I arrive without an approved visa?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">You may be refused entry and returned on the next available flight at your own cost. Never attempt to travel on a visa approval that has expired or been revoked.</p>
        </details>
    </section>
</main>
@endsection