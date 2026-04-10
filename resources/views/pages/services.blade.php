@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <div class="bg-brand-blueDeep py-20 md:py-28 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=2021&auto=format&fit=crop')] bg-cover bg-center"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight">OUR <span class="text-brand-orange">SERVICES.</span></h1>
            <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto font-light">From seamless flights to holiday homes and flexible payment plans, we handle the details so you can fiercely focus on the journey.</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-16 max-w-6xl relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-3 py-1 bg-brand-orange/10 text-brand-orange font-bold tracking-wider uppercase text-xs rounded-full mb-4">What We Offer</span>
            <h2 class="text-3xl md:text-4xl font-bold text-brand-textDark mt-3">Comprehensive Travel Solutions</h2>
            <p class="text-slate-500 mt-4 text-lg">We provide an end-to-end travel experience deliberately engineered for your supreme comfort and utmost convenience.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Flight Booking -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-16 h-16 bg-brand-blue/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-brand-blue group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-plane-departure text-2xl text-brand-blue group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-textDark mb-3">Flight Booking</h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">Access a massive global network of leading airlines to find the absolute most competitive fares. Enjoy instant electronic ticketing and a lightning-fast search engine.</p>
                <ul class="text-xs text-slate-500 space-y-2 font-medium">
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Global availability</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Unbeatable rates</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Instant confirmation</li>
                </ul>
            </div>

            <!-- Holiday Homes Stay -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-16 h-16 bg-sky-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-sky-500 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-home text-2xl text-sky-500 group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-textDark mb-3">Holiday Homes</h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">Unwind in our aggressively curated selection of premium holiday apartments and comfortable stays globally, expertly tailored exactly to your unique budget.</p>
                <ul class="text-xs text-slate-500 space-y-2 font-medium">
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Curated properties</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Verified hosts exclusively</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Fully furnished & equipped</li>
                </ul>
            </div>

            <!-- Installment Payment -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-16 h-16 bg-brand-orange/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-brand-orange group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-wallet text-2xl text-brand-orange group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-textDark mb-3">Installment Payment</h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">You shouldn't have to break the bank to see the world. Book your dream trip today and confidently spread the total cost over highly convenient installments.</p>
                <ul class="text-xs text-slate-500 space-y-2 font-medium">
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Book Now, Pay Later</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Zero hidden interest</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Flexible payment periods</li>
                </ul>
            </div>

            <!-- 24/7 Concierge & Support -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-slate-700 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-headset text-2xl text-slate-600 group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-textDark mb-3">24/7 Support Concierge</h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">Our dedicated team of human travel experts is strictly on standby to assist you. Whatever you need, day or night across time-zones, we are a single call away.</p>
                <ul class="text-xs text-slate-500 space-y-2 font-medium">
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Priority assistance</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Available across time zones</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Direct phone lines</li>
                </ul>
            </div>
            
            <!-- Travel Insurance -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-teal-500 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-shield-alt text-2xl text-teal-600 group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-textDark mb-3">Travel Insurance</h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">Travel without fear. We offer totally comprehensive travel insurance packages covering medical emergencies, unexpected trip cancellations, and lost luggage.</p>
                <ul class="text-xs text-slate-500 space-y-2 font-medium">
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Medical Coverage</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Cancellation Protection</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Lost baggage recovery</li>
                </ul>
            </div>
            
            <!-- Visa Assistance -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="w-16 h-16 bg-brand-red/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-brand-red group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-passport text-2xl text-brand-red group-hover:text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-brand-textDark mb-3">Visa Assistance</h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">Navigate complex visa procedures with total ease. Our experts meticulously guide you through application requirements and intensive documentation profiling.</p>
                <ul class="text-xs text-slate-500 space-y-2 font-medium">
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Document profiling</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Embassy appointments</li>
                    <li><i class="fas fa-check text-brand-orange mr-2"></i>Consultation and advisory</li>
                </ul>
            </div>
        </div>

        <div class="mt-20 text-center">
            <a href="{{ route('contact') }}" class="inline-block bg-brand-orange hover:bg-orange-500 text-white font-bold py-4 px-12 rounded-full shadow-lg shadow-orange-500/30 transition-transform transform hover:-translate-y-1 text-lg">
                Ready? Let's Talk.
            </a>
        </div>
    </main>
@endsection
