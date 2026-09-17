@extends('layouts.front')

@section('seo-title', 'Tax ID (TIN) Registration for Nigerians in the UK | Nurud Travels')
@section('seo-description', 'Register for your Nigerian Taxpayer Identification Number (TIN) from the UK through the Federal Inland Revenue Service and partner enrolment. Support for new registrations and TIN retrieval.')

@section('content')
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <nav class="text-xs text-gray-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1">
            <li><a href="/" class="hover:text-brand-red">Home</a></li>
            <li><span class="mx-1">/</span></li>
            <li><a href="{{ route('services') }}" class="hover:text-brand-red">Services</a></li>
            <li><span class="mx-1">/</span></li>
            <li>Tax ID (TIN) Registration</li>
        </ol>
    </nav>

    <h1 class="text-3xl sm:text-4xl font-extrabold text-brand-grayDark dark:text-white mb-4">Nigerian Taxpayer Identification Number (TIN) Registration</h1>
    <p class="text-gray-600 dark:text-slate-300 text-lg mb-8">Your Nigerian TIN is required to open business accounts, file taxes, clear goods at Nigerian ports, obtain import licences and complete countless official transactions. You can register and manage your TIN from the UK — without a trip back home.</p>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">What is a Nigerian TIN and who needs one?</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">The TIN is a unique number issued by the Federal Inland Revenue Service (FIRS) — or a State Internal Revenue Service — that identifies you or your company for tax purposes. Every taxpayer in Nigeria, including non-resident individuals who earn Nigerian income, should hold one.</p>
        <p class="text-gray-600 dark:text-slate-300">Diaspora Nigerians often discover they need a TIN when they try to open a Nigerian business account, receive rental income, apply for an import licence or clear personal goods. Having the number ready before you travel home means documents, accounts and customs paperwork can move the moment you arrive.</p>
    </section>

    <section class="bg-brand-bluePale/40 dark:bg-slate-800/60 rounded-2xl p-6 sm:p-8 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-4">What Nurud Travels arranges for you</h2>
        <ul class="space-y-3 text-gray-700 dark:text-slate-300 text-sm">
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>New TIN registration for individuals and business owners resident in the UK</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Recovery of lost or forgotten TIN numbers via FIRS channels</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Document preparation to prevent FIRS rejection and long re-submission loops</span></li>
            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-blue mt-0.5"></i><span>Guidance through our partner <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="text-brand-blue hover:text-brand-red underline">ninuk.co.uk</a> for identity-linked applications</span></li>
        </ul>
    </section>

    <section class="bg-white dark:bg-slate-800 rounded-2xl p-6 sm:p-8 border border-gray-100 dark:border-slate-700 mb-8">
        <h2 class="text-xl font-bold text-brand-grayDark dark:text-white mb-3">Before you apply</h2>
        <p class="text-gray-600 dark:text-slate-300 mb-4">Have your identity documents ready. Applying with a <a href="{{ route('services.nin-bvn') }}" class="text-brand-blue hover:text-brand-red underline">linked NIN &amp; BVN</a> makes registration considerably smoother, because FIRS validates your identity against the national identity database. If you are also expecting to travel, consider combining the process with your <a href="{{ route('flights.route.show', ['route_slug' => 'london-lhr-to-lagos-los']) }}" class="text-brand-blue hover:text-brand-red underline">flight booking to Lagos or Abuja</a>.</p>
        <details class="mb-3">
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">How quickly will I receive my TIN?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Once your application is submitted with complete documentation, FIRS processing commonly completes within a few business days. Incomplete submissions are the biggest cause of delay — our document review prevents that.</p>
        </details>
        <details>
            <summary class="font-semibold cursor-pointer text-brand-grayDark dark:text-white">Can I get a TIN for my company from the UK?</summary>
            <p class="mt-2 text-gray-600 dark:text-slate-300 text-sm">Yes. Business TIN registration can be progressed from abroad with the company's registration documents (CAC certificate) and director details. We coordinate the required signatures and identity verification.</p>
        </details>
    </section>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('contact') }}" class="bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-6 rounded-lg transition-colors text-sm">Start TIN Registration</a>
        <a href="https://ninuk.co.uk" target="_blank" rel="noopener" class="bg-transparent border border-brand-blue text-brand-blue hover:bg-brand-blue/5 py-3 px-6 rounded-lg font-bold text-sm transition-colors">Visit Our Partner</a>
    </div>
</main>
@endsection