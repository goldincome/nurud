<!-- Header -->
<header
    class="bg-white backdrop-blur-md sticky top-0 z-50 border-b border-brand-blue shadow-md shadow-brand-blue/10 relative">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center relative">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <a href="/" class="flex items-center space-x-2">
                <img src="{{ asset('images/nurud-logo.png') }}" alt="Nurud Travels" class="h-10 sm:h-12 w-auto transform scale-[1.4] md:scale-[1.45] origin-left"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <span class="text-2xl font-bold hidden" style="display:none;">
                    <span class="text-brand-blue">Nurud</span>
                    <span class="text-brand-red ml-1">Travels</span>
                </span>
            </a>
        </div>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-brand-blue">
            <a href="/"
                class="{{ request()->is('/') ? 'text-brand-red' : 'hover:text-brand-red' }} transition-colors">Home</a>
            <a href="#"
                class="{{ request()->is('services*') ? 'text-brand-red' : 'hover:text-brand-red' }} transition-colors">Services</a>
            <a href="#"
                class="{{ request()->is('about*') ? 'text-brand-red' : 'hover:text-brand-red' }} transition-colors">About
                Us</a>
            <a href="#"
                class="{{ request()->is('contact*') ? 'text-brand-red' : 'hover:text-brand-red' }} transition-colors">Contact
                Us</a>
            <a href="#"
                class="{{ request()->is('blog*') ? 'text-brand-red' : 'hover:text-brand-red' }} transition-colors">Blog</a>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-3">
            @guest
                <!-- Login Button -->
                <a href="{{ route('login') }}"
                    class="hidden md:block bg-transparent hover:bg-brand-blue/5 text-brand-blue px-4 py-2 rounded-full text-sm font-medium transition-all border border-brand-blue">
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
                class="md:hidden text-brand-blue hover:text-brand-blue/80 focus:outline-none p-2 ml-1 relative z-[60]"
                onclick="const m = document.getElementById('mobile-menu'); if(m.style.display === 'none' || m.classList.contains('hidden')){ m.style.display = 'block'; m.classList.remove('hidden'); } else { m.style.display = 'none'; m.classList.add('hidden'); }">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" style="display: none;"
        class="md:hidden absolute top-full left-0 right-0 w-full bg-white shadow-xl border-t border-brand-blue/10 z-[100] animate-fade-in-down">
        <div class="flex flex-col p-4 space-y-4 text-sm font-medium text-brand-blue">
            <a href="/"
                class="{{ request()->is('/') ? 'text-brand-red' : 'hover:text-brand-red' }} hover:bg-slate-50 p-2 rounded transition-colors">Home</a>
            <a href="#"
                class="{{ request()->is('about*') ? 'text-brand-red' : 'hover:text-brand-red' }} hover:bg-slate-50 p-2 rounded transition-colors">About
                Us</a>
            <a href="#"
                class="{{ request()->is('financing*') ? 'text-brand-red' : 'hover:text-brand-red' }} hover:bg-slate-50 p-2 rounded transition-colors">Travel
                Financing</a>
            <a href="#"
                class="{{ request()->is('contact*') ? 'text-brand-red' : 'hover:text-brand-red' }} hover:bg-slate-50 p-2 rounded transition-colors">Contact
                Us</a>
            <a href="#"
                class="{{ request()->is('blog*') ? 'text-brand-red' : 'hover:text-brand-red' }} hover:bg-slate-50 p-2 rounded transition-colors">Blog</a>

            <!-- Auth Section for Mobile -->
            <div class="border-t border-brand-blue/10 pt-4 mt-2">
                @guest
                    <a href="{{ route('login') }}"
                        class="block w-full text-center bg-transparent border border-brand-blue text-brand-blue py-2 rounded-lg mb-2 hover:bg-brand-blue/5 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center bg-brand-red text-white py-2 rounded-lg hover:bg-brand-redDark transition-colors">
                        Sign Up
                    </a>
                @endguest

                @auth
                    <div class="text-center mb-2">
                        <span class="text-brand-blue/50 text-xs">Welcome,</span>
                        <span
                            class="font-semibold text-brand-blue">{{ auth()->user()->first_name ? auth()->user()->first_name : auth()->user()->name }}</span>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="block w-full text-center py-2 rounded-lg mb-2 hover:bg-brand-blue/5 text-brand-blue transition-colors">
                        <i class="fas fa-user mr-2"></i>My Account
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-center py-2 rounded-lg hover:bg-brand-blue/5 text-brand-blue/70 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>