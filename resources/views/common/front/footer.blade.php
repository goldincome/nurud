<!-- Footer -->
<footer class="bg-brand-blueDeep text-white mt-0 pt-12 pb-6">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8 text-sm text-white/60 mb-10">
            {{-- Brand Column --}}
            <div class="col-span-2 md:col-span-1">
                <a href="/" class="block mb-4">
                    <img src="{{ asset('images/nurud-logo.png') }}" alt="Nurud Travels" class="h-10 w-auto" onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                    <span class="text-2xl font-bold text-white hidden">
                        <span class="text-white">Nurud</span>
                        <span class="text-brand-red ml-1">Travels</span>
                    </span>
                </a>
                <p class="text-white/50 text-xs leading-relaxed mb-4">Your journey begins here. Search, compare, and book flights to destinations worldwide.</p>
                <div class="flex space-x-4 text-lg">
                    <a href="#" class="text-white/40 hover:text-brand-red transition-colors"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white/40 hover:text-brand-red transition-colors"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white/40 hover:text-brand-red transition-colors"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white/40 hover:text-brand-red transition-colors"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            {{-- Company --}}
            <div>
                <h5 class="text-white font-bold mb-4 text-xs uppercase tracking-wider">Company</h5>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Travel Financing</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                </ul>
            </div>
            {{-- Support --}}
            <div>
                <h5 class="text-white font-bold mb-4 text-xs uppercase tracking-wider">Support</h5>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Refund Policy</a></li>
                </ul>
            </div>
            {{-- Explore --}}
            <div>
                <h5 class="text-white font-bold mb-4 text-xs uppercase tracking-wider">Explore</h5>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition-colors">Trending Cities</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Trending Countries</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Flight Deals</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Price Alerts</a></li>
                </ul>
            </div>
            {{-- Contact --}}
            <div>
                <h5 class="text-white font-bold mb-4 text-xs uppercase tracking-wider">Contact</h5>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2"><i class="fas fa-map-marker-alt mt-0.5 text-brand-red text-xs"></i> Lagos, Nigeria</li>
                    <li class="flex items-start gap-2"><i class="fas fa-phone mt-0.5 text-brand-red text-xs"></i> +234 700 000 0000</li>
                    <li class="flex items-start gap-2"><i class="fas fa-envelope mt-0.5 text-brand-red text-xs"></i> info@nurud.com</li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs text-white/30">&copy; 2026 Nurud Travels. All Rights Reserved.</p>
            <div class="flex items-center gap-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/200px-Mastercard-logo.svg.png" alt="Mastercard" class="h-5 opacity-40">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/200px-Visa_Inc._logo.svg.png" alt="Visa" class="h-3 opacity-40">
            </div>
        </div>
    </div>
</footer>

<!-- Floating Chat Icon -->
<div class="fixed bottom-6 right-6 z-50">
    <button
        class="bg-brand-blue hover:bg-brand-blueDark text-white rounded-full w-14 h-14 shadow-lg flex items-center justify-center text-2xl transition-all hover:scale-110 hover:shadow-xl">
        <i class="fas fa-comment-dots"></i>
    </button>
</div>