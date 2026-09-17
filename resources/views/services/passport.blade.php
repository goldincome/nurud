@extends('layouts.front')

@section('seo-title', 'Nigerian Passport Renewal in London from the UK | Nurud Travels')
@section('seo-description', 'Renew or replace your Nigerian passport from London and across the UK. We guide you through the Nigeria Immigration Service application, biometrics and delivery — without the stress.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('services') }}" class="hover:text-brand-red">Services</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Nigerian Passport Renewal</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Nigerian Passport Renewal in London</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">A valid Nigerian passport is your ticket to everything: flying home, banking, visas and proving your identity abroad. Renewing it from the UK no longer means endless trips and guesswork. Nurud Travels walks you through every step — from online application to passport in hand.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">How Nigerian passport renewal from the UK works</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">Passport applications for Nigerians abroad are processed by the Nigeria Immigration Service (NIS) through the official online portal. Renewals, corrections and fresh applications all begin online, after which you attend a biometric appointment and have the finished passport delivered.</p>
        <ol class="space-y-3 text-gray-700 dark:text-slate-300 text-sm list-decimal list-inside">
            <li><strong class="text-brand-grayDark dark:text-white">Online application</strong> — complete the NIS form accurately, with the correct passport type and duration (5 years or 10 years).</li>
            <li><strong class="text-brand-grayDark dark:text-white">Document upload</strong> — passport photo, BVN/NIN details, and proof required by the immigration service.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Payment</strong> — pay the applicable renewal fee safely through the official channel.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Biometric appointment</strong> — attend your UK-facing embassy or partner appointment to capture fingerprints and photo.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Dispatch</strong> — your new passport is printed and dispatched to your UK address.</li>
        </ol>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">Why use Nurud Travels for your passport?</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>We help you choose the correct passport option and avoid costly rejections</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Document checklist review so nothing is missing when the NIS assesses your file</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Guidance via our partner <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="text-brand-blue hover:text-brand-red underline">ninuk.co.uk</a> for identity and documentation support</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Booking support for flights home if you need to complete the process while in Nigeria</span></li>
        </ul>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Common questions about renewing from the UK</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">How long does a Nigerian passport renewal take?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Once your application is approved and biometrics are captured, production and dispatch usually take a few weeks. Delays are most common when documents are rejected, which is why accuracy at the start matters. Our full walkthrough is covered in the <a href="{{ route('guides.passport-renewal') }}" class="text-brand-blue hover:text-brand-red underline">passport renewal guide</a>.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Do I need my NIN or BVN to renew my passport?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">The NIS generally requires your National Identification Number as part of the application. If you do not have one, sort out your <a href="{{ route('services.nin-bvn') }}" class="text-brand-blue hover:text-brand-red underline">NIN/BVN enrolment</a> before starting.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Can I travel with less than six months on my passport?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Many destinations, including Nigeria's neighbours, require six months of validity. If your passport is running low, plan your renewal early and pair it with your <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">flights to Lagos</a>.</p>
        </details>
    </section>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('contact') }}" class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm">Get Passport Assistance</a>
        <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="bg-transparent border border-brand-blue text-brand-blue hover:bg-brand-blue/5 py-3 px-6 rounded-lg font-bold text-sm transition-colors">Visit Our Partner</a>
    </div>
</main>
@endsection