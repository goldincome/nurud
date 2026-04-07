<!-- Header -->
<header class="bg-brand-blueDeep/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-white/5">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center relative">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <a href="/" class="flex items-center space-x-2">
                <img src="{{ asset('images/nurud-logo.png') }}" alt="Nurud Travels" class="h-10 sm:h-12 w-auto"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <span class="text-2xl font-bold text-white hidden" style="display:none;">
                    <span class="text-white">Nurud</span>
                    <span class="text-brand-red ml-1">Travels</span>
                </span>
            </a>
        </div>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-white/70">
            <a href="/" class="hover:text-white transition-colors">Home</a>
            <a href="#" class="hover:text-white transition-colors">Services</a>
            <a href="#" class="hover:text-white transition-colors">About Us</a>
            <a href="#" class="hover:text-white transition-colors">Contact Us</a>
            <a href="#" class="hover:text-white transition-colors">Blog</a>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-3">
            @guest
                <!-- Login Button -->
                <a href="{{ route('login') }}"
                    class="hidden md:block bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium transition-all border border-white/20">
                    Login
                </a>
                <!-- Sign Up Button -->
                <a href="{{ route('register') }}"
                    class="hidden md:block bg-brand-red hover:bg-brand-redDark text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Sign Up
                </a>
            @endguest

            @auth
                <!-- User Dropdown -->
                <div class="relative">
                    <button type="button" id="user-dropdown-btn"
                        onclick="document.getElementById('user-dropdown').classList.toggle('hidden')"
                        class="flex items-center space-x-2 cursor-pointer bg-brand-red text-white px-3 py-1.5 rounded-full text-sm font-medium hover:bg-brand-redDark transition-colors">
                        <span>{{ auth()->user()->first_name ? auth()->user()->first_name[0] : auth()->user()->name[0] }}</span>
                        <span
                            class="hidden sm:inline">{{ auth()->user()->first_name ? auth()->user()->first_name : auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-slate-200 py-2 z-50">
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-blue transition-colors">
                            <i class="fas fa-user mr-2"></i>My Account
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-brand-blue transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <!-- Mobile Menu Button -->
            <button type="button" id="mobile-menu-btn"
                class="md:hidden text-white/80 hover:text-white focus:outline-none p-2 ml-1 relative z-[60]"
                onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu"
        class="hidden md:hidden absolute top-full left-0 right-0 bg-brand-blueDeep shadow-xl border-t border-white/10 z-[100] animate-fade-in-down">
        <div class="flex flex-col p-4 space-y-4 text-sm font-medium text-white/80">
            <a href="/" class="hover:text-white hover:bg-white/10 p-2 rounded transition-colors">Home</a>
            <a href="#" class="hover:text-white hover:bg-white/10 p-2 rounded transition-colors">About Us</a>
            <a href="#" class="hover:text-white hover:bg-white/10 p-2 rounded transition-colors">Travel
                Financing</a>
            <a href="#" class="hover:text-white hover:bg-white/10 p-2 rounded transition-colors">Contact Us</a>
            <a href="#" class="hover:text-white hover:bg-white/10 p-2 rounded transition-colors">Blog</a>

            <!-- Auth Section for Mobile -->
            <div class="border-t border-white/10 pt-4 mt-2">
                @guest
                    <a href="{{ route('login') }}"
                        class="block w-full text-center bg-white/10 text-white py-2 rounded-lg mb-2 hover:bg-white/20 transition-colors border border-white/20">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center bg-brand-red text-white py-2 rounded-lg hover:bg-brand-redDark transition-colors">
                        Sign Up
                    </a>
                @endguest

                @auth
                    <div class="text-center mb-2">
                        <span class="text-white/50 text-xs">Welcome,</span>
                        <span
                            class="font-semibold text-white">{{ auth()->user()->first_name ? auth()->user()->first_name : auth()->user()->name }}</span>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="block w-full text-center py-2 rounded-lg mb-2 hover:bg-white/10 text-white transition-colors">
                        <i class="fas fa-user mr-2"></i>My Account
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-center py-2 rounded-lg hover:bg-white/10 text-white/70 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>