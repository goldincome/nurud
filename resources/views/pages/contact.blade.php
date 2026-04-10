@extends('layouts.front')

@section('content')
    <!-- Hero Section -->
    <div class="bg-brand-blueDeep py-20 md:py-28 relative overflow-hidden">
        <div
            class="absolute inset-0 opacity-10 bg-[url('https://images.unsplash.com/photo-1596524430615-b46475ddff6e?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center">
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight">GET IN <span
                    class="text-brand-orange">TOUCH.</span></h1>
            <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto font-light">We're here to help you plan your next
                journey. Whether you have a question about our fares, need booking support, or just want to explore
                possibilities—drop us a line.</p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-16 max-w-6xl relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Contact Information Side -->
            <div class="lg:col-span-1 space-y-8">
                <div>
                    <h2 class="text-3xl font-bold text-brand-textDark mb-6">Contact Info</h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">Our support team is available around the clock to assist
                        you with anything you need. Reach out directly using the details below.</p>
                </div>

                <div class="space-y-6">
                    <!-- Address -->
                    <div
                        class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                        <div
                            class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center flex-shrink-0 text-brand-orange group-hover:bg-brand-orange group-hover:text-white transition-colors">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-textDark mb-1">Our Location</h4>
                            <p class="text-slate-500 text-sm leading-relaxed">Unit 6, Block 3 Woolwich, Dockyard Industrial
                                Estate, Woolwich Church St, Charlton, London SE18 5PQ</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div
                        class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                        <div
                            class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center flex-shrink-0 text-sky-500 group-hover:bg-sky-500 group-hover:text-white transition-colors">
                            <i class="fas fa-phone-alt text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-textDark mb-1">Call Us</h4>
                            <a href="tel:+442032474747" class="text-slate-500 text-sm hover:text-brand-blue block">+44 (0)
                                203 247 4747</a>
                            <p class="text-[10px] text-sky-600 font-bold uppercase tracking-wider mt-2"><i
                                    class="fas fa-circle text-[6px] mr-1"></i> Available 24/7</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div
                        class="flex items-start gap-4 p-5 bg-white rounded-2xl border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                        <div
                            class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center flex-shrink-0 text-brand-red group-hover:bg-brand-red group-hover:text-white transition-colors">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-textDark mb-1">Email Us</h4>
                            <a href="mailto:info@nurud.com"
                                class="text-slate-500 text-sm hover:text-brand-blue">info@nurud.com</a>
                        </div>
                    </div>
                </div>

                <!-- Social Proof -->
                <div class="pt-8 mt-8 border-t border-slate-100">
                    <h4 class="font-bold text-slate-400 mb-4 text-xs uppercase tracking-widest">Connect Internally</h4>
                    <div class="flex space-x-3">
                        <a href="#"
                            class="w-10 h-10 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#"
                            class="w-10 h-10 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#"
                            class="w-10 h-10 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#"
                            class="w-10 h-10 bg-white shadow-sm border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form Side -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 relative overflow-hidden">
                    <!-- Decor overlay -->
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-brand-blueDeep opacity-5 rounded-bl-[100px] pointer-events-none">
                    </div>

                    <h3 class="text-2xl md:text-3xl font-bold text-brand-textDark mb-3">Send us a message</h3>
                    <p class="text-slate-500 mb-8 text-sm md:text-base">Fill out the form below and our dedicated support
                        team will get back to you as soon as possible.</p>

                    <form action="#" method="POST" class="space-y-6 relative z-10">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Your
                                    Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="far fa-user text-slate-300"></i>
                                    </div>
                                    <input type="text" name="name" placeholder="John Doe" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 text-sm focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all placeholder:text-slate-300">
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email
                                    Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="far fa-envelope text-slate-300"></i>
                                    </div>
                                    <input type="email" name="email" placeholder="john@example.com" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 text-sm focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all placeholder:text-slate-300">
                                </div>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Subject</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="far fa-bookmark text-slate-300"></i>
                                </div>
                                <input type="text" name="subject" placeholder="How can we help?" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 text-sm focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all placeholder:text-slate-300">
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Message</label>
                            <textarea name="message" rows="6" placeholder="Tell us more about your inquiry..." required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-all resize-none placeholder:text-slate-300"></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full md:w-auto bg-brand-orange hover:bg-orange-500 text-white font-bold py-4 px-10 rounded-xl shadow-lg shadow-orange-500/30 transition-transform transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                <span>Send Message</span>
                                <i class="fas fa-paper-plane text-sm"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>


@endsection