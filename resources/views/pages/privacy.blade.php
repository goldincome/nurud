@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <div class="bg-brand-blueDeep py-16 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 bg-[url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop')] bg-cover bg-center mix-blend-overlay"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Privacy Policy</h1>
            <p class="text-white/80 text-sm md:text-base">Last updated: {{ date('F d, Y') }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-16 max-w-4xl">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-14 space-y-10">
            <section>
                <div class="mb-5 inline-block px-4 py-1.5 bg-brand-blue/10 text-brand-blue font-bold tracking-wider uppercase text-xs rounded-full">Our Commitment</div>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-textDark mb-5">1. Information We Collect</h2>
                <p class="text-slate-600 text-sm md:text-base leading-loose">
                    At Nurud Travels, we are deeply committed to protecting your privacy and securing your personal information. When you use our platform to search for flights or make a booking, we may collect personally identifiable information such as your name, email address, phone number, payment details, and passenger data. 
                </p>
                <p class="text-slate-600 text-sm md:text-base leading-loose mt-3">
                    We also automatically collect device data, IP addresses, and browsing behavior via cookies to improve the speed, usability, and personalization of our platform over time.
                </p>
            </section>

            <div class="w-full h-px bg-slate-100"></div>

            <section>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-textDark mb-5">2. How We Use Your Data</h2>
                <p class="text-slate-600 text-sm md:text-base leading-loose mb-4">
                    Your personal information is leveraged strictly to enhance your booking experience. Specifically, we confidently use your data to:
                </p>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <i class="fas fa-check-circle text-brand-blue mt-1.5"></i>
                        <span class="text-slate-600 text-sm md:text-base leading-relaxed">Process transactions securely and issue your electronic tickets successfully.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <i class="fas fa-check-circle text-brand-blue mt-1.5"></i>
                        <span class="text-slate-600 text-sm md:text-base leading-relaxed">Communicate critical itinerary changes, flight delays, or booking confirmations.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <i class="fas fa-check-circle text-brand-blue mt-1.5"></i>
                        <span class="text-slate-600 text-sm md:text-base leading-relaxed">Provide immediate, context-aware 24/7 customer support tailored to your trip.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <i class="fas fa-check-circle text-brand-blue mt-1.5"></i>
                        <span class="text-slate-600 text-sm md:text-base leading-relaxed">Continuously optimize our core algorithms for better, unadvertised search results.</span>
                    </li>
                </ul>
            </section>

            <div class="w-full h-px bg-slate-100"></div>

            <section>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-textDark mb-5">3. Data Sharing and Third Parties</h2>
                <p class="text-slate-600 text-sm md:text-base leading-loose">
                    We absolutely do not sell, rent, or trade your personal information. To actually fulfill your bookings, it is strictly necessary to share relevant details (such as names and passport numbers combined) directly with airlines, global distribution systems (GDS), and official payment gateways. These third-party partners are legally and strictly obligated to protect your data according to robust global security standards.
                </p>
            </section>

            <div class="w-full h-px bg-slate-100"></div>

            <section>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-textDark mb-5">4. Security Measures</h2>
                <p class="text-slate-600 text-sm md:text-base leading-loose">
                    Security is intentionally woven into the very fabric of Nurud Travels. We explicitly employ industry-standard encryption protocols (SSL/TLS) for dynamic data transmission. Our payment processing complies rigidly with strict PCI-DSS standards, decisively ensuring that your sensitive payment data is never stored locally on our servers.
                </p>
            </section>

            <div class="w-full h-px bg-slate-100"></div>

            <section>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-textDark mb-5">5. Your Rights and Choices</h2>
                <p class="text-slate-600 text-sm md:text-base leading-loose">
                    You have total control over your personal data. You reserve the absolute right to access, radically modify, or permanently delete your account information at any time. You can also easily opt-out of marketing communications by intuitively updating your preferences in your dashboard or by clicking "Unsubscribe" in our promotional emails.
                </p>
            </section>

            <div class="w-full h-px bg-slate-100"></div>

            <section>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-textDark mb-5">6. Policy Updates</h2>
                <p class="text-slate-600 text-sm md:text-base leading-loose">
                    We may confidently update this Privacy Policy occasionally to accurately reflect adjustments in our physical practices or strict compliance with legal mandates. We warmly encourage you to review this page periodically. Continued use of our seamless platform directly constitutes your acknowledgment and explicit agreement to any modifications made.
                </p>
            </section>
        </div>
    </main>
@endsection
