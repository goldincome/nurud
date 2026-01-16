<!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center relative">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-brand-blue">247travels<span class="text-brand-orange">.</span></span>
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
                    <!-- Profile/Currency -->
                    <div class="flex items-center space-x-1 cursor-pointer bg-brand-orange text-white px-3 py-1 rounded-full text-sm font-medium">
                        <span>C</span>
                        <span class="hidden sm:inline">Chukwu</span>
                        <i class="fas fa-chevron-down text-xs ml-1"></i>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden text-slate-600 hover:text-brand-blue focus:outline-none p-2 ml-1">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 right-0 bg-white shadow-lg border-t border-slate-100 z-50 animate-fade-in-down">
                <div class="flex flex-col p-4 space-y-4 text-sm font-medium text-slate-600">
                    <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Home</a>
                    <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">About Us</a>
                    <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Travel Financing</a>
                    <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Contact Us</a>
                    <a href="#" class="hover:text-brand-blue hover:bg-slate-50 p-2 rounded transition-colors">Blog</a>
                </div>
            </div>
        </header>