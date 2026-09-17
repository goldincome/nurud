@extends('layouts.front')

@section('seo-title', 'NIN & BVN Enrolment Centres in the UK | Nurud Travels')
@section('seo-description', 'Where to enrol for your Nigerian NIN or link your BVN in the UK. Find registration centres, appointment guidance and document requirements for Nigerians living in London and beyond.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('guides.index') }}" class="hover:text-brand-red">Travel Guides</a></li>
            <li><span class="mx-1">/</span></li>
            <li>NIN &amp; BVN Centres in the UK</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">NIN &amp; BVN Enrolment Centres in the UK</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">You do not need to fly back to Nigeria to get your National Identification Number or fix your BVN. Enrolment partners in the UK capture your biometrics and submit them to the NIMC on your behalf. Here is how to find the right centre and prepare.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">How UK enrolment centres work</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">The National Identity Management Commission (NIMC) licenses a network of enrolment partners internationally. A licensed UK centre records your fingerprints, facial image and signature, verifies your documents, and submits the enrolment to NIMC. Once processed, you receive your NIN.</p>
        <p class="text-gray-600 dark:text-slate-300">For BVN, enrolment is different: your biometrics are captured for linking with your Nigerian bank accounts through the Nigeria Inter-Bank Settlement System (NIBSS). If you already have a BVN but cannot remember it, a partner can help you recover it without travel.</p>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Preparing for your appointment</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Bring a valid passport (or BRP) — your identity document must match your application</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Confirm the centre is NIMC-licensed before you pay anything</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Check whether your name/date of birth must match your Nigerian documents</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>For BVN linkage, know your linked Nigerian bank and sort code</span></li>
        </ul>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Enrolment support through our partner</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">We arrange NIN and BVN appointments for clients across the UK through <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="text-brand-blue hover:text-brand-red underline">ninuk.co.uk</a>. They handle eligibility checks, booking, biometric capture and status follow-up — see the full offer on our <a href="{{ route('services.nin-bvn') }}" class="text-brand-blue hover:text-brand-red underline">NIN/BVN enrolment service page</a>.</p>
        <p class="text-gray-600 dark:text-slate-300 text-sm">Getting your NIN sorted in advance also smooths your <a href="{{ route('services.passport') }}" class="text-brand-blue hover:text-brand-red underline">passport renewal</a> and any Nigerian banking you plan to do when you next travel home.</p>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Questions we hear most</h2>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Is NIN enrolment in the UK the same as in Nigeria?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">The biometric process is the same and your NIN is issued nationally — but you cannot obtain a NIN by post or purely online. An in-person appointment at a licensed UK partner is required for first-time enrolment.</p>
        </details>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">How long does the NIN take after enrolment?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">NIMC processing typically completes within a few weeks after submission, though it can take longer. We track status for our clients and chase overdue cases.</p>
        </details>
    </section>
</main>
@endsection