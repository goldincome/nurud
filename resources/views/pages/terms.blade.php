@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <div class="bg-brand-blueDeep py-16 md:py-24">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Terms and Conditions</h1>
            <p class="text-white/80 text-sm md:text-base">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 md:p-12 space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-brand-textDark mb-4">1. Introduction</h2>
                <p class="text-slate-600 text-sm leading-loose">
                    Welcome to Nurud Travels. These terms and conditions outline the rules and regulations for the use of our booking platform. 
                    By accessing this website, we assume you accept these terms and conditions. Do not continue to use the website if you do not agree to take all of the terms and conditions stated on this page.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brand-textDark mb-4">2. Bookings and Reservations</h2>
                <p class="text-slate-600 text-sm leading-loose mb-3">
                    All bookings are subject to availability and acceptance. When you make a booking, you guarantee that you have the authority to accept and do accept the terms of these booking conditions on behalf of your party.
                </p>
                <ul class="list-disc pl-6 text-slate-600 text-sm space-y-2 leading-loose">
                    <li>Fares are subject to change until confirmed.</li>
                    <li>You are responsible for ensuring all traveler details exactly match the government-issued ID.</li>
                    <li>Passports, visas, and health requirements are the strict responsibility of the traveler.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brand-textDark mb-4">3. Payments and Pricing</h2>
                <p class="text-slate-600 text-sm leading-loose">
                    Prices are shown in the applicable currency and may be subject to exchange rate fluctuations. Full payment is required at the time of booking unless otherwise stated. We reserve the right to cancel any booking if payment is not received in full in the expected timeframe.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brand-textDark mb-4">4. Cancellations and Refunds</h2>
                <p class="text-slate-600 text-sm leading-loose">
                    Cancellation policies vary depending on the airline and the specific fare purchased. Many flight tickets are completely non-refundable and non-transferable. If you wish to cancel or modify your booking, you must contact our customer service team. Any applicable refunds or credits will be processed in strict accordance with the operating airline's rules.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brand-textDark mb-4">5. Limitation of Liability</h2>
                <p class="text-slate-600 text-sm leading-loose">
                    Our liability is limited to the maximum extent permitted by law. We act as an agent for the travel providers (including airlines, hotels, and transfer services) and are not liable for their acts, errors, omissions, representations, warranties, breaches, or negligence, or for any personal injuries, death, property damage, or other damages or expenses resulting therefrom.
                </p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brand-textDark mb-4">6. Contact Information</h2>
                <p class="text-slate-600 text-sm leading-loose">
                    If you have any queries regarding any of our terms and conditions, please contact our customer support team for further clarification via the contact details provided on our website.
                </p>
            </section>
        </div>
    </main>
@endsection
