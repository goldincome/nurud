@extends('layouts.front')

@section('content')
    <!-- Main Container -->

       

 <!-- Hero Section -->
        <main>
            <section class="hero-banner h-[70vh] md:h-[80vh] flex items-center justify-center relative">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="text-center relative z-10 px-4">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Your Journey Begins Here</h1>
                    <p class="text-lg md:text-xl text-white/90" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Discover and book flights to your dream destinations with ease.</p>
                </div>
            </section>

            <div class="relative -mt-[15vh] md:-mt-48 z-20">
                <!-- Add booking form -->
                @include('common.front.error-and-message')
                @include('common.front.booking-form')
                <!-- Deals Section -->
                <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <h2 class="text-2xl font-bold mb-6 text-slate-800 dark:text-white">Top Flight Deals</h2>
                    <div class="deals-container flex overflow-x-auto space-x-6 pb-4">
                        <!-- Deal Card 1 -->
                        <div class="flex-shrink-0 w-80 h-96 rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1502602898657-3e91760c0337?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Paris">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white">
                                <h3 class="text-2xl font-bold">Paris, France</h3>
                                <p class="text-lg font-medium">From $650</p>
                            </div>
                        </div>
                        <!-- Deal Card 2 -->
                        <div class="flex-shrink-0 w-80 h-96 rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1542051841857-5f90071e7989?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Tokyo">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white">
                                <h3 class="text-2xl font-bold">Tokyo, Japan</h3>
                                <p class="text-lg font-medium">From $820</p>
                            </div>
                        </div>
                        <!-- Deal Card 3 -->
                        <div class="flex-shrink-0 w-80 h-96 rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1533929736458-ca588913c835?q=80&w=1974&auto=format&fit=crop" class="w-full h-full object-cover" alt="Sydney">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white">
                                <h3 class="text-2xl font-bold">Sydney, Australia</h3>
                                <p class="text-lg font-medium">From $1100</p>
                            </div>
                        </div>
                        <!-- Deal Card 4 -->
                        <div class="flex-shrink-0 w-80 h-96 rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1519677100203-a0e668c97489?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Rome">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white">
                                <h3 class="text-2xl font-bold">Rome, Italy</h3>
                                <p class="text-lg font-medium">From $580</p>
                            </div>
                        </div>
                        <!-- Deal Card 5 -->
                        <div class="flex-shrink-0 w-80 h-96 rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1523731407960-2a22d33f829f?q=80&w=1974&auto=format&fit=crop" class="w-full h-full object-cover" alt="Bali">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-6 text-white">
                                <h3 class="text-2xl font-bold">Bali, Indonesia</h3>
                                <p class="text-lg font-medium">From $950</p>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Why Choose Us Section -->
                <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <h2 class="text-2xl font-bold text-center mb-8 text-slate-800 dark:text-white">Why Choose Us?</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-sky-100 dark:bg-sky-900 mx-auto mb-4">
                                <i class="fas fa-tags text-2xl text-sky-600 dark:text-sky-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Best Price Guarantee</h3>
                            <p class="text-slate-600 dark:text-slate-400">We offer the most competitive prices on flights to destinations worldwide.</p>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-sky-100 dark:bg-sky-900 mx-auto mb-4">
                                <i class="fas fa-headset text-2xl text-sky-600 dark:text-sky-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">24/7 Customer Support</h3>
                            <p class="text-slate-600 dark:text-slate-400">Our team is available around the clock to assist you with any inquiries.</p>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-sky-100 dark:bg-sky-900 mx-auto mb-4">
                                <i class="fas fa-check-circle text-2xl text-sky-600 dark:text-sky-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Easy Booking</h3>
                            <p class="text-slate-600 dark:text-slate-400">Book your flight in just a few clicks with our user-friendly interface.</p>
                        </div>
                    </div>
                </section>

                <!-- CTA Section -->
                <section class="bg-brand-blue">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
                        <h2 class="text-3xl font-bold text-white mb-4">Ready for Your Next Adventure?</h2>
                        <p class="text-sky-100 text-lg mb-8">Find the best flight deals and book your dream vacation today!</p>
                        <button class="cta-btn bg-white text-brand-blue font-bold py-3 px-8 rounded-lg hover:bg-slate-100 transition-colors">Book Your Flight Now</button>
                    </div>
                </section>

                <!-- Holiday Resort Section -->
                <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <h2 class="text-2xl font-bold mb-6 text-slate-800 dark:text-white">Explore Holiday Resorts</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?q=80&w=1949&auto=format&fit=crop" class="w-full h-64 object-cover" alt="Maldives Resort">
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="absolute bottom-0 left-0 p-4 text-white">
                                <h3 class="text-xl font-bold">Maldives</h3>
                            </div>
                        </div>
                        <div class="rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop" class="w-full h-64 object-cover" alt="Cancun Resort">
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="absolute bottom-0 left-0 p-4 text-white">
                                <h3 class="text-xl font-bold">Cancun</h3>
                            </div>
                        </div>
                        <div class="rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070&auto=format&fit=crop" class="w-full h-64 object-cover" alt="Bali Resort">
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="absolute bottom-0 left-0 p-4 text-white">
                                <h3 class="text-xl font-bold">Bali</h3>
                            </div>
                        </div>
                        <div class="rounded-lg shadow-lg overflow-hidden relative group">
                            <img src="https://images.unsplash.com/photo-1563911302283-d2bc129e7570?q=80&w=1935&auto=format&fit=crop" class="w-full h-64 object-cover" alt="Santorini Resort">
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="absolute bottom-0 left-0 p-4 text-white">
                                <h3 class="text-xl font-bold">Santorini</h3>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Popular Countries Section -->
                <section class="bg-slate-100 dark:bg-slate-800 py-12">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <h2 class="text-2xl font-bold text-center mb-8 text-slate-800 dark:text-white">Popular Countries to Visit</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?q=80&w=1974&auto=format&fit=crop" class="w-full h-32 object-cover rounded-lg mb-2" alt="Italy">
                                <p class="font-semibold">Italy</p>
                            </div>
                            <div class="text-center">
                                <img src="https://images.unsplash.com/photo-1505761671935-60b3a7427508?q=80&w=2070&auto=format&fit=crop" class="w-full h-32 object-cover rounded-lg mb-2" alt="Spain">
                                <p class="font-semibold">Spain</p>
                            </div>
                            <div class="text-center">
                                <img src="https://images.unsplash.com/photo-1524492412937-b28074a5d7da?q=80&w=2071&auto=format&fit=crop" class="w-full h-32 object-cover rounded-lg mb-2" alt="India">
                                <p class="font-semibold">India</p>
                            </div>
                            <div class="text-center">
                                <img src="https://images.unsplash.com/photo-1517713982677-4b6e3c2aa27e?q=80&w=1974&auto=format&fit=crop" class="w-full h-32 object-cover rounded-lg mb-2" alt="Thailand">
                                <p class="font-semibold">Thailand</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- CTA Section 2 -->
                <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-4">Your Journey Awaits!</h2>
                    <p class="text-slate-600 dark:text-slate-400 text-lg mb-8">Don't wait any longer. The world is out there.</p>
                    <button class="cta-btn bg-brand-blue text-white font-bold py-3 px-8 rounded-lg hover:bg-sky-700 transition-colors">Book Your Flight Now</button>
                </section>
            </div>
        </main>

@endsection

