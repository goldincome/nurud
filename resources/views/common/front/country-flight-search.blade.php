<section class="px-1" aria-label="Search flights to {{ $country['name'] }}">
    @php
        $gateway = $country['gateway_code'] ?? '';
        $departureDefault = date('Y-m-d', strtotime('+7 days'));
        $returnDefault = date('Y-m-d', strtotime('+14 days'));
    @endphp
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-700">
        <h2 class="text-lg font-bold text-brand-grayDark dark:text-white mb-1">Find flights to {{ $country['name'] }}</h2>
        <p class="text-xs text-gray-500 dark:text-slate-400 mb-4">Search live fares from the UK to {{ $country['name'] }} in seconds.</p>
        <form action="{{ route('search') }}" method="POST">
            @csrf
            <input type="hidden" name="routeModel" value="1">
            <input type="hidden" name="flightClass" value="ECONOMY">
            <input type="hidden" name="travelers[numberOfAdults]" value="1">
            <input type="hidden" name="travelers[numberOfChildren]" value="0">
            <input type="hidden" name="travelers[numberOfInfants]" value="0">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                <div class="relative autocomplete">
                    <label for="origin" class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Where From?</label>
                    <div class="relative">
                        <i class="fas fa-plane-departure absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50"></i>
                        <input type="text" id="origin" name="originLocationCode" placeholder="Where From? (e.g. LHR)" value="LHR"
                            class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                    </div>
                </div>
                <div class="relative autocomplete">
                    <label for="destination" class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Going To?</label>
                    <div class="relative">
                        <i class="fas fa-plane-arrival absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50"></i>
                        <input type="text" id="destination" name="originDestinationCode" placeholder="Going To?" value="{{ $gateway }}"
                            class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                <div class="relative">
                    <label for="departure-date" class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Departure</label>
                    <div class="relative">
                        <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50 pointer-events-none"></i>
                        <input type="text" id="departure-date" name="departureDate" placeholder="Select date" value="{{ $departureDefault }}"
                            class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                    </div>
                </div>
                <div class="relative">
                    <label for="return-date" class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Returning</label>
                    <div class="relative">
                        <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50 pointer-events-none"></i>
                        <input type="text" id="return-date" name="returnDate" placeholder="Select date" value="{{ $returnDefault }}"
                            class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full md:w-auto bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-8 rounded-xl transition-colors duration-300 shadow-lg shadow-brand-red/20 text-sm">
                <i class="fas fa-search mr-2"></i>Search Flights
            </button>
        </form>
    </div>
</section>

@include('common.front.search-overlay')