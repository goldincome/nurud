@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <div class="bg-brand-blueDeep py-20 md:py-28 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight">YOUR JOURNEY, <span class="text-brand-orange">ELEVATED.</span></h1>
            <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto font-light">We are Nurud Travels. Redefining how you experience the world by making travel seamless, accessible, and completely extraordinary.</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-16 max-w-5xl">
        <!-- Story Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
            <div class="space-y-6">
                <span class="inline-block px-3 py-1 bg-brand-orange/10 text-brand-orange font-bold tracking-wider uppercase text-xs rounded-full">Who We Are</span>
                <h2 class="text-3xl md:text-4xl font-bold text-brand-textDark leading-tight">More Than Just Flights.</h2>
                <p class="text-slate-600 leading-relaxed text-lg">
                    Nurud Travels isn't just another booking platform. We are a dedicated team of passionate explorers, tech innovators, and travel experts obsessed with removing the friction from your journey. We believe that seeing the world shouldn't be a hassle—it should be the absolute thrill of a lifetime.
                </p>
                <p class="text-slate-600 leading-relaxed text-lg">
                    By combining cutting-edge search technology with deep industry relationships, we deliver an unmatched booking experience. From hidden gems across the globe to bustling metropolises, we get you there with zero stress.
                </p>
            </div>
            <div class="rounded-3xl overflow-hidden shadow-2xl relative h-[350px] md:h-[450px]">
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2074&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105" alt="A beautiful sky representing travel">
            </div>
        </div>

        <!-- Mission Statement -->
        <div class="bg-gradient-to-br from-slate-50 to-white rounded-[2.5rem] p-10 md:p-16 mb-20 text-center shadow-lg border border-slate-100/50">
            <h2 class="text-2xl font-bold text-slate-400 mb-6 uppercase tracking-widest text-sm">Our Mission</h2>
            <p class="text-2xl md:text-3xl text-brand-blue font-semibold max-w-4xl mx-auto leading-snug">
                "To aggressively negotiate the best fares, engineer the absolute smoothest booking flow, and provide uncompromising support—so that all you ever have to think about is packing your bags."
            </p>
        </div>

        <!-- Core Values / Why Us -->
        <div class="space-y-12">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3 py-1 bg-brand-blue/10 text-brand-blue font-bold tracking-wider uppercase text-xs rounded-full mb-4">The Nurud Advantage</span>
                <h2 class="text-3xl md:text-4xl font-bold text-brand-textDark mt-3">Why Travelers Choose Us</h2>
                <p class="text-slate-500 mt-4 text-lg">We didn't just build a search engine. We built a travel partner you can reliably depend on, completely.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pillar 1 -->
                <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:border-brand-blue/20 transition-all duration-300 transform hover:-translate-y-1 group">
                    <div class="w-16 h-16 bg-brand-blue/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-blue group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-tags text-2xl text-brand-blue group-hover:text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-textDark mb-4">Unbeatable Fares</h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        We don't just find flights; we hunt down the best unadvertised deals across the globe. Our smart algorithms ensure you never overpay for your seat.
                    </p>
                </div>
                
                <!-- Pillar 2 -->
                <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:border-brand-orange/20 transition-all duration-300 transform hover:-translate-y-1 group">
                    <div class="w-16 h-16 bg-brand-orange/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-orange group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-bolt text-2xl text-brand-orange group-hover:text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-textDark mb-4">Frictionless Experience</h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        No endless loading screens. No confusing layouts. Just a sleek, blazing-fast, and deeply intuitive interface built to get you booked in minutes.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:border-sky-500/20 transition-all duration-300 transform hover:-translate-y-1 group">
                    <div class="w-16 h-16 bg-sky-100 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-sky-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-headset text-2xl text-sky-500 group-hover:text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-brand-textDark mb-4">Uncompromising Support</h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        When plans change or issues arise, bots simply won't cut it. Our dedicated team of human travel experts is on standby 24/7 to sort it out.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="mt-24 bg-brand-blue rounded-3xl p-12 text-center relative overflow-hidden shadow-2xl">
            <!-- Decorative circle -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-brand-orange opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to see the world?</h2>
                <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">Stop dreaming about your next destination and start planning. Book your next flight with confidence and ease.</p>
                <a href="/" class="inline-block bg-brand-orange hover:bg-orange-500 text-white font-bold py-4 px-12 rounded-full shadow-lg transition-transform transform hover:scale-105 text-lg">
                    Start Searching Fares
                </a>
            </div>
        </div>
    </main>
@endsection
