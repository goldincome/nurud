<section id="booking-section" class="px-4">
    <div class="container mx-auto max-w-6xl">
        <form action="{{ route('search') }}" method="POST">
            @csrf
            @php
                $searchData = session('last_flight_search', []);
            @endphp
            <div id="booking-bar"
                class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl border border-slate-200/80 dark:border-slate-700">
                <div class="flex flex-col md:flex-row items-center gap-3 mb-4">
                    <div class="relative w-full md:w-auto">
                        <select id="trip-type" name="routeModel"
                            class="w-full appearance-none bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-4 pr-10 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50 [&>option]:text-brand-blueDark font-semibold">
                            <option value="1" {{ (isset($searchData['routeModel']) && $searchData['routeModel'] == 1) ? 'selected' : ( !isset($searchData['routeModel']) ? 'selected' : '') }}>Round Trip</option>
                            <option value="0" {{ (isset($searchData['routeModel']) && $searchData['routeModel'] == 0) ? 'selected' : '' }}>One Way</option>
                            <option value="2" {{ (isset($searchData['routeModel']) && $searchData['routeModel'] == 2) ? 'selected' : '' }}>Multi-City</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-brand-blue/70 pointer-events-none text-xs"></i>
                    </div>

                    <div class="relative w-full md:w-auto">
                        <input type="hidden" name="travelers[numberOfAdults]" id="input-adults" value="{{ $searchData['travelers']['numberOfAdults'] ?? 1 }}">
                        <input type="hidden" name="travelers[numberOfChildren]" id="input-children" value="{{ $searchData['travelers']['numberOfChildren'] ?? 0 }}">
                        <input type="hidden" name="travelers[numberOfInfants]" id="input-infants" value="{{ $searchData['travelers']['numberOfInfants'] ?? 0 }}">

                        <button type="button" id="passenger-btn"
                            class="w-full text-left bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 px-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50 flex items-center justify-between">
                            @php
                                $passengerCount = isset($searchData['travelers']) ? ($searchData['travelers']['numberOfAdults'] ?? 1) + ($searchData['travelers']['numberOfChildren'] ?? 0) + ($searchData['travelers']['numberOfInfants'] ?? 0) : 1;
                            @endphp
                            <span><i class="fas fa-user mr-2 text-brand-blue/70"></i><span id="passenger-count-text">{{ $passengerCount }} Passenger{{ $passengerCount > 1 ? 's' : '' }}</span></span>
                            <i class="fas fa-chevron-down text-brand-blue/70 text-xs"></i>
                        </button>

                        <div id="passenger-dropdown"
                            class="hidden absolute top-full mt-2 w-72 bg-white dark:bg-slate-800 rounded-xl shadow-xl p-4 z-50 border dark:border-slate-700">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-brand-blueDark text-sm">Adults</p>
                                        <p class="text-xs text-brand-blueDark font-medium dark:text-brand-blue/70">Ages
                                            12+</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-type="adults" data-action="decrease"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-sm">-</button>
                                        <span id="adults-count" class="w-8 text-center font-bold text-sm">{{ $searchData['travelers']['numberOfAdults'] ?? 1 }}</span>
                                        <button type="button" data-type="adults" data-action="increase"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-sm">+</button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-brand-blueDark text-sm">Children</p>
                                        <p class="text-xs text-brand-blueDark font-medium dark:text-brand-blue/70">Ages
                                            2-11</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-type="children" data-action="decrease"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-sm">-</button>
                                        <span id="children-count" class="w-8 text-center font-bold text-sm">{{ $searchData['travelers']['numberOfChildren'] ?? 0 }}</span>
                                        <button type="button" data-type="children" data-action="increase"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-sm">+</button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-brand-blueDark text-sm">Infants</p>
                                        <p class="text-xs text-brand-blueDark font-medium dark:text-brand-blue/70">Under
                                            2</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-type="infants" data-action="decrease"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-sm">-</button>
                                        <span id="infants-count" class="w-8 text-center font-bold text-sm">{{ $searchData['travelers']['numberOfInfants'] ?? 0 }}</span>
                                        <button type="button" data-type="infants" data-action="increase"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-sm">+</button>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4 border-slate-200 dark:border-slate-600">
                            <button type="button" id="passenger-done-btn"
                                class="w-full bg-brand-blue hover:bg-brand-blueDark text-white font-bold py-2 px-4 rounded-lg text-sm">Done</button>
                        </div>
                    </div>

                    <div class="relative w-full md:w-auto">
                        <select id="cabin-class" name="flightClass"
                            class="w-full appearance-none bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-4 pr-10 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50 [&>option]:text-brand-blueDark font-semibold">
                            <option value="ECONOMY" {{ (isset($searchData['flightClass']) && $searchData['flightClass'] === 'ECONOMY') ? 'selected' : ( !isset($searchData['flightClass']) ? 'selected' : '') }}>Economy</option>
                            <option value="PREMIUM_ECONOMY" {{ (isset($searchData['flightClass']) && $searchData['flightClass'] === 'PREMIUM_ECONOMY') ? 'selected' : '' }}>Premium Economy</option>
                            <option value="BUSINESS" {{ (isset($searchData['flightClass']) && $searchData['flightClass'] === 'BUSINESS') ? 'selected' : '' }}>Business</option>
                            <option value="FIRST" {{ (isset($searchData['flightClass']) && $searchData['flightClass'] === 'FIRST') ? 'selected' : '' }}>First</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-brand-blue/70 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <div id="single-flight-form" class="grid grid-cols-1 md:grid-cols-10 gap-3 items-end">
                        <div class="col-span-12 md:col-span-3 relative autocomplete">
                            <label for="origin"
                                class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Where
                                From?</label>
                            <div class="relative">
                                <i
                                    class="fas fa-plane-departure absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50"></i>
                                <input type="text" id="origin" name="originLocationCode"
                                    placeholder="Where From? (e.g. JFK)" value="{{ $searchData['originLocationCode'] ?? '' }}"
                                    class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-3 relative autocomplete">
                            <label for="destination"
                                class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Going
                                To?</label>
                            <div class="relative">
                                <i
                                    class="fas fa-plane-arrival absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50"></i>
                                <input type="text" id="destination" name="originDestinationCode" placeholder="Going To?"
                                    value="{{ $searchData['originDestinationCode'] ?? '' }}"
                                    class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-2">
                            <label for="departure-date"
                                class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Departure</label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50 pointer-events-none"></i>
                                <input type="text" id="departure-date" name="departureDate" placeholder="Select date" value="{{ $searchData['departureDate'] ?? '' }}"
                                    class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                            </div>
                        </div>
                        <div id="return-date-wrapper" class="col-span-12 md:col-span-2" style="{{ (isset($searchData['routeModel']) && $searchData['routeModel'] != 1) ? 'display: none;' : '' }}">
                            <label for="return-date"
                                class="block text-xs font-bold text-brand-blueDark dark:text-brand-blue/70 mb-1">Returning</label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-brand-blue/50 pointer-events-none"></i>
                                <input type="text" id="return-date" name="returnDate" placeholder="Select date" value="{{ $searchData['returnDate'] ?? '' }}"
                                    class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 pl-10 pr-4 text-brand-blueDark font-semibold dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/50">
                            </div>
                        </div>
                    </div>

                    @if(isset($searchData['routeModel']) && $searchData['routeModel'] == 2)
                    <script>
                        window.PREVIOUS_MULTI_CITY_DATA = [
                            @foreach(range(1, 6) as $i)
                                @if(!empty($searchData['originLocationCode'.$i]))
                                {
                                    origin: "{{ $searchData['originLocationCode'.$i] }}",
                                    destination: "{{ $searchData['originDestinationCode'.$i] ?? '' }}",
                                    date: "{{ $searchData['departureDate'.$i] ?? '' }}"
                                },
                                @endif
                            @endforeach
                        ];
                    </script>
                    @endif

                    <div id="multi-city-form" class="{{ (isset($searchData['routeModel']) && $searchData['routeModel'] == 2) ? 'block' : 'hidden' }}">
                        <div id="multi-city-rows" class="space-y-4">
                        </div>
                    </div>
                </div>

                <div
                    class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center flex-wrap gap-4 md:gap-6 w-full md:w-auto">
                        <label id="flexible-dates-wrapper" class="flex items-center cursor-pointer">
                            <input type="checkbox" name="dateWindow" {{ (isset($searchData['dateWindow']) && $searchData['dateWindow']) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 text-brand-blue focus:ring-brand-blue/50">
                            <span class="ml-2 text-xs font-semibold text-brand-blueDark dark:text-brand-blue/70">+/- 3
                                days</span>
                        </label>
                        <button type="button" id="add-flight-btn"
                            class="hidden text-xs font-bold text-brand-blueDark hover:text-brand-blue">
                            <i class="fas fa-plus mr-1"></i>Add flight
                        </button>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="directFlightOnly" {{ (isset($searchData['directFlightOnly']) && $searchData['directFlightOnly']) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 text-brand-blue focus:ring-brand-blue/50">
                            <span class="ml-2 text-xs font-semibold text-brand-blueDark dark:text-brand-blue/70">Direct
                                flight</span>
                        </label>
                    </div>
                    <div class="w-full md:w-auto">
                        <button
                            class="w-full md:w-auto bg-brand-red hover:bg-brand-redDark text-white font-bold py-3 px-8 rounded-xl transition-colors duration-300 shadow-lg shadow-brand-red/20 text-sm">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@include('common.front.search-overlay')