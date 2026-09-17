@extends('layouts.front')

@section('seo-title', 'NIN & BVN Enrolment for Nigerians in the UK | Nurud Travels')
@section('seo-description', 'Enrol for your National Identification Number (NIN) or link your Bank Verification Number (BVN) from the UK. Trusted NIN/BVN enrolment assistance for Nigerians in London and across the UK.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('services') }}" class="hover:text-brand-red">Services</a></li>
            <li><span class="mx-1">/</span></li>
            <li>NIN &amp; BVN Enrolment</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">NIN &amp; BVN Enrolment for Nigerians in the UK</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Your Nigerian National Identification Number (NIN) and Bank Verification Number (BVN) are the keys to banking, SIM registration, travel documents and government services back home. You do not need to fly to Nigeria to sort them out — we help you do it from the UK.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">What is the difference between NIN and BVN?</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">The <strong>NIN</strong> is your unique 11-digit identity issued by Nigeria's National Identity Management Commission (NIMC). It proves who you are and is now required for almost every government or financial transaction in Nigeria — including opening a bank account, renewing a passport, getting a driver's licence and receiving money abroad.</p>
        <p class="text-gray-600 dark:text-slate-300">The <strong>BVN</strong> is your biometric banking identifier that ties your fingerprints and photograph to all your Nigerian bank accounts. Banks use it to fight fraud and, increasingly, to verify international transfers and comply with anti-money-laundering rules. If you bank with a Nigerian bank while living in the UK, linking your BVN to your accounts keeps them active and secure.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Can I enrol for NIN or BVN from the UK?</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">Yes. Enrolling for a new NIN from abroad requires a registered UK-based enrolment centre that captures your biometrics and submits them to NIMC. For existing NINs, the National Identity Number "linkage" service lets you combine your NIN with the National Identification Number tied to your BVN without travelling.</p>
        <p class="text-gray-600 dark:text-slate-300">Through our trusted partner <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="text-brand-blue hover:text-brand-red underline">ninuk.co.uk</a>, we handle the full process for you: eligibility checks, biometric capture appointments, form preparation and status tracking.
        </p>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">How Nurud Travels helps</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Step-by-step guidance for first-time NIN enrolment while resident in the UK</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>BVN linkage and NIN-BVN harmonisation support with your Nigerian bank</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Document review to make sure your application is approved the first time</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Assistance for families and children who may need an NIN before travelling to Nigeria</span></li>
        </ul>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Questions we answer every week</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">How long does NIN enrolment from the UK take?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Biometric capture typically takes one appointment. NIMC processing after submission normally completes within a few weeks, but times vary. We track the status for you and chase where needed.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Do I need an appointment to enrol for NIN in London?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Yes, first-time enrolment requires an in-person biometric appointment at a UK enrolment partner. Through ninuk.co.uk we arrange the earliest available slot near you.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Can I get a Nigerian passport renewed before I have my NIN?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">The Nigeria Immigration Service normally requires your NIN as part of passport processing. If you do not have one, we recommend booking your NIN enrolment first — see our <a href="{{ route('services.passport') }}" class="text-brand-blue hover:text-brand-red underline">passport renewal guide</a>.</p>
        </details>
    </section>

    <div class="flex flex-wrap gap-3">
        <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm">Start NIN/BVN Enrolment</a>
        <a href="{{ route('contact') }}" class="bg-transparent border border-brand-blue text-brand-blue hover:bg-brand-blue/5 py-3 px-6 rounded-lg font-bold text-sm transition-colors">Talk to a Travel Advisor</a>
    </div>
</main>
@endsection