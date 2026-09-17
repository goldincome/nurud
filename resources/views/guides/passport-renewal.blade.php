@extends('layouts.front')

@section('seo-title', 'How to Renew a Nigerian Passport from the UK | Nurud Travels')
@section('seo-description', 'Step-by-step guide to renewing your Nigerian passport from the UK: NIS online application, biometrics appointment, fees and delivery — plus what to do if your NIN or BVN needs sorting first.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('guides.index') }}" class="hover:text-brand-red">Travel Guides</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Passport Renewal Guide</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">How to Renew a Nigerian Passport from the UK</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Renewing a Nigerian passport from the UK has never been fully online, but it is now far more predictable. This guide walks the whole journey — application, payment, biometrics and delivery — so you can renew without losing your head or your money.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Before you apply: the checklist</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Current Nigerian passport (expired passports are acceptable for renewal in most cases)</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Your <a href="{{ route('guides.nin-bvn-centres') }}" class="text-brand-blue hover:text-brand-red underline">National Identification Number (NIN)</a> — required at the appointment</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Digital passport photograph meeting the NIS photo guidelines (white background)</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Valid payment card for the renewal fee (varies by passport duration and location)</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Proof of UK residence (e.g. BRP, visa vignette or council correspondence)</span></li>
        </ul>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">The renewal process, step by step</h2>
        <ol class="space-y-3 text-gray-700 dark:text-slate-300 text-sm list-decimal list-inside">
            <li><strong class="text-brand-grayDark dark:text-white">Create an NIS account</strong> — register on the official Nigeria Immigration Service passport portal with your email and details.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Fill the application form</strong> — application type (fresh/renewal), passport class (standard or enhanced) and 5- or 10-year validity.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Select your processing mission</strong> — London or your nearest UK embassy, or a UK NIS-approved centre.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Pay the fee online</strong> — use the official payment gateway and save your receipt/confirmation reference.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Book a biometric appointment</strong> — NIS practice varies by location; attend with your documents and have your fingerprints and photo captured.</li>
            <li><strong class="text-brand-grayDark dark:text-white">Track and receive</strong> — your passport is printed and posted back. Allow several weeks; check the NIS tracking tool.</li>
        </ol>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Where Nurud Travels and our partners help</h2>
        <p class="text-gray-600 dark:text-slate-300 text-sm mb-3">We guide you through the portal, catch errors before the NIS rejects your file, and coordinate appointments through our partner <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="text-brand-blue hover:text-brand-red underline">ninuk.co.uk</a> — who also handle the <a href="{{ route('services.nin-bvn') }}" class="text-brand-blue hover:text-brand-red underline">NIN and BVN enrolment</a> you may need first. For the full service overview, see our <a href="{{ route('services.passport') }}" class="text-brand-blue hover:text-brand-red underline">passport renewal service page</a>.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Common pitfalls</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">My passport photo keeps getting rejected</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">NIS photos must meet strict specifications: plain white background, no glasses, full face, correct dimensions. Most rejections are photo issues — get these checked before you pay.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">I have no NIN yet</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Sort this before applying. Book <a href="{{ route('services.nin-bvn') }}" class="text-brand-blue hover:text-brand-red underline">NIN enrolment in the UK</a> ahead of your passport appointment so your biometrics are on file.</p>
        </details>
    </section>
</main>
@endsection