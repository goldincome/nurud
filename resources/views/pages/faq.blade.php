@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <div class="bg-brand-blueDeep py-20 md:py-28 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1542204165-65bf26472b9b?q=80&w=2074&auto=format&fit=crop')] bg-cover bg-center"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight">WE'VE GOT <span class="text-brand-orange">ANSWERS.</span></h1>
            <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto font-light">Find quick answers to the most common questions about booking your flights, payment methods, and managing your journey.</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-16 max-w-4xl relative z-10">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 mb-16">
            <h2 class="text-3xl font-bold text-brand-textDark mb-8 text-center">Frequently Asked Questions</h2>
            
            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="border border-slate-100 rounded-2xl p-6 hover:shadow-md hover:border-brand-blue/30 transition-all group cursor-pointer" onclick="this.classList.toggle('bg-slate-50')">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-brand-textDark group-hover:text-brand-blue transition-colors">How do I search for a flight?</h3>
                        <i class="fas fa-chevron-down text-slate-300 group-hover:text-brand-blue transition-colors"></i>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mt-3">Simply navigate to our homepage, enter your departure and destination cities, select your travel dates, and click the "Search" button. Our powerful engine will instantly fetch the best available fares.</p>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="border border-slate-100 rounded-2xl p-6 hover:shadow-md hover:border-brand-blue/30 transition-all group cursor-pointer" onclick="this.classList.toggle('bg-slate-50')">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-brand-textDark group-hover:text-brand-blue transition-colors">Are the prices shown final?</h3>
                        <i class="fas fa-chevron-down text-slate-300 group-hover:text-brand-blue transition-colors"></i>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mt-3">The prices displayed during your search are accurate at the time of query. However, airline fares fluctuate rapidly. A price is only guaranteed once your payment is confirmed and the ticket is successfully issued.</p>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="border border-slate-100 rounded-2xl p-6 hover:shadow-md hover:border-brand-blue/30 transition-all group cursor-pointer" onclick="this.classList.toggle('bg-slate-50')">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-brand-textDark group-hover:text-brand-blue transition-colors">What payment methods do you accept?</h3>
                        <i class="fas fa-chevron-down text-slate-300 group-hover:text-brand-blue transition-colors"></i>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mt-3">We accept all major credit and debit cards, secure bank transfers, and flexible travel financing options depending on your location. All transactions are securely processed directly on our platform.</p>
                </div>
                
                <!-- FAQ Item 4 -->
                <div class="border border-slate-100 rounded-2xl p-6 hover:shadow-md hover:border-brand-blue/30 transition-all group cursor-pointer" onclick="this.classList.toggle('bg-slate-50')">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-brand-textDark group-hover:text-brand-blue transition-colors">Can I modify or cancel my booking?</h3>
                        <i class="fas fa-chevron-down text-slate-300 group-hover:text-brand-blue transition-colors"></i>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mt-3">Modifications and cancellations depend entirely on the specific fare rules set by the airline for the ticket you purchased. Non-refundable tickets cannot be refunded. Reach out to our 24/7 support team to explore your available options.</p>
                </div>
                
                <!-- FAQ Item 5 -->
                <div class="border border-slate-100 rounded-2xl p-6 hover:shadow-md hover:border-brand-blue/30 transition-all group cursor-pointer" onclick="this.classList.toggle('bg-slate-50')">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold text-brand-textDark group-hover:text-brand-blue transition-colors">How will I receive my ticket?</h3>
                        <i class="fas fa-chevron-down text-slate-300 group-hover:text-brand-blue transition-colors"></i>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mt-3">Upon successful payment confirmation, your electronic ticket (e-ticket) will be immediately sent to the email address provided during booking. You can also securely access and download it from your user dashboard at any time.</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-12 bg-sky-50/50 rounded-2xl p-10 border border-sky-100 shadow-inner">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-sky-500 shadow-sm">
                <i class="fas fa-life-ring text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-brand-textDark mb-3">Still need help?</h3>
            <p class="text-slate-600 mb-8 max-w-md mx-auto">If you couldn't find the answer to your question, our human support team is ready to assist you right now.</p>
            <a href="{{ route('contact') }}" class="inline-block bg-brand-orange hover:bg-orange-500 text-white font-bold py-4 px-10 rounded-xl shadow-lg transition-transform transform hover:-translate-y-1 hover:shadow-orange-500/30">
                Contact Support
            </a>
        </div>
    </main>
@endsection
