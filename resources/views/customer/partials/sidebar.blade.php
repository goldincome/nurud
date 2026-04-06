<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
    <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-brand-blue/5 to-transparent">
        <div class="flex items-center space-x-3">
            <div
                class="w-12 h-12 rounded-full bg-brand-orange text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-brand-orange/20">
                {{ auth()->user()->first_name ? auth()->user()->first_name[0] : auth()->user()->name[0] }}
            </div>
            <div>
                <h3 class="font-bold text-slate-800 leading-tight">
                    {{ auth()->user()->first_name ? auth()->user()->first_name . ' ' . auth()->user()->last_name : auth()->user()->name }}
                </h3>
                <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
    <nav class="p-4 space-y-1">
        <x-customer-nav-link href="{{ route('customer.dashboard') }}" :active="request()->routeIs('customer.dashboard')"
            icon="fas fa-th-large">
            Dashboard
        </x-customer-nav-link>

        <x-customer-nav-link href="{{ route('customer.bookings.index') }}"
            :active="request()->routeIs('customer.bookings.*')" icon="fas fa-history">
            Booking History
        </x-customer-nav-link>

        <x-customer-nav-link href="{{ route('customer.payments.index') }}"
            :active="request()->routeIs('customer.payments.*')" icon="fas fa-credit-card">
            Payments
        </x-customer-nav-link>

        <x-customer-nav-link href="{{ route('customer.profile.index') }}"
            :active="request()->routeIs('customer.profile.index')" icon="fas fa-user-circle">
            Profile
        </x-customer-nav-link>

        <x-customer-nav-link href="{{ route('customer.profile.change-password') }}"
            :active="request()->routeIs('customer.profile.change-password')" icon="fas fa-key">
            Change Password
        </x-customer-nav-link>

        <div class="pt-4 mt-4 border-t border-slate-50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-rose-600 hover:bg-rose-50 transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>