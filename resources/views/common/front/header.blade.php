<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center relative">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <span class="text-2xl font-bold text-brand-blue">Nurud<span class="text-brand-orange">.</span></span>
        </div>

        <!-- Desktop Nav Links -->
        <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-slate-600">
            <a href="#" class="hover:text-brand-blue transition-colors">Home</a>
            <a href="#" class="hover:text-brand-blue transition-colors">About Us</a>
            <a href="#" class="hover:text-brand-blue transition-colors">Travel Financing</a>
            <a href="#" class="hover:text-brand-blue transition-colors">Contact Us</a>
            <a href="#" class="hover:text-brand-blue transition-colors">Blog</a>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-3">
            @guest
                <!-- Login Button -->
                <a href="{{ route('login') }}"
                    class="hidden md:block bg-brand-blue hover:bg-sky-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Login
                </a>
                <!-- Sign Up Button -->
                <a href="{{ route('register') }}"
                    class="hidden md:block bg-brand-orange hover:bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    Sign Up
                </a>
            @endguest

            @auth
                <!-- User Dropdown -->
                <div class="relative">
                    <button type="button" id="user-dropdown-btn"
                        onclick="document.getElementById('user-dropdown').classList.toggle('hidden')"
                        class="flex items-center space-x-2 cursor-pointer bg-brand-orange text-white px-3 py-1 rounded-full text-sm font-medium hover:bg-orange-600 transition-colors">
                        <span>{{ auth()->user()->first_name ? auth()->user()->first_name[0] : auth()->user()->name[0] }}</span>
                        <span
                            class="hidden sm:inline">{{ auth()->user()->first_name ? auth()->user()->first_name : auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-dropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-2 z-50">
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
                class="md:hidden text-slate-600 hover:text-brand-blue focus:outline-none p-2 ml-1 relative z-[60]">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu"
        class="hidden md:hidden absolute top-full left-0 right-0 bg-white shadow-lg border-t border-slate-100 z-[100] animate-fade-in-down">
        <div class="flex flex-col p-4 space-y-4 text-sm font-medium text-slate-600">
            <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Home</a>
            <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">About Us</a>
            <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Travel
                Financing</a>
            <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Contact Us</a>
            <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Blog</a>

            <!-- Auth Section for Mobile -->
            <div class="border-t border-slate-200 pt-4 mt-2">
                @guest
                    <a href="{{ route('login') }}"
                        class="block w-full text-center bg-brand-blue text-white py-2 rounded-lg mb-2 hover:bg-sky-700 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center bg-brand-orange text-white py-2 rounded-lg hover:bg-orange-600 transition-colors">
                        Sign Up
                    </a>
                @endguest

                @auth
                    <div class="text-center mb-2">
                        <span class="text-slate-500 text-xs">Welcome,</span>
                        <span
                            class="font-semibold text-brand-textDark">{{ auth()->user()->first_name ? auth()->user()->first_name : auth()->user()->name }}</span>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="block w-full text-center py-2 rounded-lg mb-2 hover:bg-slate-50 text-brand-blue transition-colors">
                        <i class="fas fa-user mr-2"></i>My Account
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-center py-2 rounded-lg hover:bg-slate-50 text-slate-700 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</header>