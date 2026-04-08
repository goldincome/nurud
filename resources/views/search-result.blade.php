@extends('layouts.front')

@section('content')
    <div class="container mx-auto px-4 py-4 pb-6">
        <div id="modify-bar" class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div
                class="flex-1 w-full bg-[#163A66] rounded-lg p-3 flex flex-wrap md:flex-nowrap items-center justify-between gap-4 border border-white/10 shadow-lg">
                <div class="flex items-center gap-6 text-sm flex-1">
                    <div class="flex flex-col">
                        <span class="text-white/60 text-xs uppercase">From</span>
                        <span class="text-white font-bold">{{ $origin }}</span>
                    </div>
                    <i class="fas fa-arrow-right text-white/40"></i>
                    <div class="flex flex-col">
                        <span class="text-white/60 text-xs uppercase">To</span>
                        <span class="text-white font-bold">{{ $destination }}</span>
                    </div>
                    <div class="hidden lg:flex w-px h-8 bg-white/20 mx-2"></div>
                    <div class="hidden lg:flex flex-col">
                        <span class="text-white/60 text-xs uppercase">Departing</span>
                        <span class="text-white font-bold">{{ $tripDate }}</span>
                    </div>
                    @if($returnDate)
                    <div class="hidden lg:flex flex-col">
                        <span class="text-white/60 text-xs uppercase">Returning</span>
                        <span class="text-white font-bold">{{ $returnDate }}</span>
                    </div>
                    @endif
                    <div class="hidden xl:flex flex-col">
                        <span class="text-white/60 text-xs uppercase">Travelers</span>
                        <span class="text-white font-bold">{{ $travelersCount }}</span>
                    </div>
                    <div class="hidden xl:flex flex-col">
                        <span class="text-white/60 text-xs uppercase">Cabin</span>
                        <span class="text-white font-bold">{{ $flightClass }}</span>
                    </div>
                    <div class="hidden xl:flex flex-col">
                        <span class="text-white/60 text-xs uppercase">Flights Found</span>
                        <span class="text-white font-bold">{{ count($flights) }}</span>
                    </div>
                </div>

                <button id="modify-search-btn" onclick="toggleSearchForm()"
                    class="bg-brand-orange hover:bg-brand-orangeHover text-white px-5 py-2 rounded font-semibold text-sm transition-colors whitespace-nowrap shadow-md">
                    Modify Search
                </button>
            </div>
        </div>

        <div id="search-form" class="hidden mt-6 relative overflow-hidden rounded-2xl shadow-xl">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, #151F55 0%, #2E3B82 40%, #4A5BA0 80%, #5B6CB5 100%);"></div>
            <div class="relative z-10 pt-16 pb-6 px-2 md:px-0">
                @include('common.front.booking-form')
            </div>
        </div>
    </div>

    <main class="container mx-auto px-4 py-6">


        <div class="flex justify-between items-center mb-6">
            <h1 class="text-lg font-semibold text-slate-700">
                Flight Results from <span class="text-sky-600">{{ $origin }}</span> to <span class="text-sky-600">{{
        $destination }}</span>
            </h1>
            <button class="text-sky-600 text-sm font-medium hover:underline">
                New Price Calendar <i class="fas fa-chevron-down ml-1"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <aside class="lg:col-span-3 space-y-6">
                <div class="bg-white rounded-lg shadow-sm p-5 border border-slate-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-slate-800">Filter</h3>
                        <a href="#" onclick="resetFilters(event)" class="text-xs text-brand-orange hover:underline">Clear
                            Filters</a>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold text-sm mb-3 text-slate-700">Stops</h4>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" value="Direct"
                                    class="filter-stop rounded text-brand-blue focus:ring-brand-blue border-slate-300">
                                <span class="text-sm text-slate-600">Non-stop Flights</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" value="1 Stop"
                                    class="filter-stop rounded text-brand-blue focus:ring-brand-blue border-slate-300">
                                <span class="text-sm text-slate-600">1 Stop</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" value="2 Stops"
                                    class="filter-stop rounded text-brand-blue focus:ring-brand-blue border-slate-300">
                                <span class="text-sm text-slate-600">2 Stops</span>
                            </label>
                        </div>
                    </div>

                    <hr class="border-slate-100 mb-6">

                    <div class="mb-6">
                        <h4 class="font-semibold text-sm mb-3 text-slate-700">Airlines</h4>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            {{-- All Airlines master checkbox --}}
                            <label class="flex items-center space-x-2 cursor-pointer pb-2 mb-1 border-b border-slate-100">
                                <input type="checkbox" id="filter-airline-all"
                                    class="rounded text-brand-blue focus:ring-brand-blue border-slate-300">
                                <span class="text-sm font-bold text-brand-blue">All Airlines</span>
                            </label>
                            @if (count($airlines) > 0)
                                @foreach ($airlines as $index => $airline)
                                    @if ($index !== 0)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" value="{{ $airline['name'] }}"
                                                class="filter-airline rounded text-brand-blue focus:ring-brand-blue border-slate-300">
                                            <span class="text-sm text-slate-600">{{ $airline['name'] }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            @endif

                        </div>
                    </div>

                    <hr class="border-slate-100 mb-6">

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold text-sm text-slate-700">Max Price</h4>
                            <span id="price-display" class="text-xs font-bold text-brand-blue">{{
        config('app.currency_symbol')
                                                                                                }}5,000,000</span>
                        </div>
                        <input type="range" id="price-slider" min="500" max="5000000" step="500" value="5000000"
                            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-500 mt-2">
                            <span>{{ config('app.currency_symbol') }}500</span>
                            <span>{{ config('app.currency_symbol') }}5M+</span>
                        </div>
                    </div>

                    <hr class="border-slate-100 mb-6">

                    <div class="mb-2">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold text-sm text-slate-700">Max Duration</h4>
                            <span id="duration-display" class="text-xs font-bold text-brand-blue">50h</span>
                        </div>
                        <input type="range" id="duration-slider" min="10" max="50" step="1" value="50"
                            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-500 mt-2">
                            <span>10h</span>
                            <span>50h+</span>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-9 space-y-4">

                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                    <div class="flex items-center">
                        <button id="scroll-left-btn"
                            class="scroll-btn flex-shrink-0 w-10 h-16 bg-slate-50 hover:bg-slate-100 border-r border-slate-200 flex items-center justify-center cursor-pointer transition-colors"
                            onclick="scrollAirlines('left')">
                            <i class="fas fa-chevron-left text-slate-600"></i>
                        </button>

                        <div id="airline-scroll-container" class="flex-1 overflow-hidden">
                            <div id="airline-scroll-content"
                                class="flex divide-x divide-slate-100 transition-transform duration-300">
                                <div
                                    class="flex-none w-32 bg-slate-50 p-3 flex flex-col justify-center items-center text-center">
                                    <span class="text-xs font-bold text-slate-500">All Airlines</span>
                                    <span class="text-xs text-slate-400 mt-1">Best Price</span>
                                </div>

                                @foreach($airlineGroups as $airlineGroup)
                                                        <div class="flex-none w-32 p-3 flex flex-col justify-center items-center text-center hover:bg-slate-50 cursor-pointer transition"
                                                            onclick="filterBySingleAirline('{{ $airlineGroup['airline'] }}')">
                                                            <img src="https://pics.avs.io/200/60/{{ $airlineGroup['airlineCode'] }}.png"
                                                                class="h-6 object-contain mb-2" alt="{{ $airlineGroup['airlineCode'] }}">
                                                            <span class="text-xs font-bold text-slate-700">{{
                                    $airlineGroup['cheapestPriceFormatted'] }}</span>
                                                        </div>
                                @endforeach
                            </div>
                        </div>

                        <button id="scroll-right-btn"
                            class="scroll-btn flex-shrink-0 w-10 h-16 bg-slate-50 hover:bg-slate-100 border-l border-slate-200 flex items-center justify-center cursor-pointer transition-colors"
                            onclick="scrollAirlines('right')">
                            <i class="fas fa-chevron-right text-slate-600"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm text-slate-500 px-2">
                    <span>Sort By:</span>
                    <div class="flex space-x-4">
                        <button id="sort-cheapest"
                            class="sort-btn text-white bg-brand-blue px-4 py-1 rounded-full text-xs font-medium transition"
                            onclick="setSort('cheapest')">Cheapest</button>
                        <button id="sort-fastest"
                            class="sort-btn hover:text-brand-blue px-4 py-1 rounded-full text-xs font-medium transition"
                            onclick="setSort('fastest')">Fastest</button>
                        <button id="sort-recommended"
                            class="sort-btn hover:text-brand-blue px-4 py-1 rounded-full text-xs font-medium transition"
                            onclick="setSort('recommended')">Recommended</button>
                    </div>
                </div>

                <div id="flight-list" class="space-y-4">
                </div>

                <div id="load-more-wrapper" class="text-center pt-4 hidden">
                    <p id="flight-count-info" class="text-sm text-slate-500 mb-3"></p>
                    <button id="load-more-btn" onclick="loadMoreFlights()"
                        class="bg-brand-blue hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-transform hover:scale-105 flex items-center justify-center mx-auto">
                        Load more flights <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                </div>

            </div>
        </div>


    </main>

@endsection

@include('common.front.booking-overlay')

<div id="credit-facility-modal"
    class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 transform scale-95 transition-transform duration-300"
        id="credit-modal-content">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-xl">
            <h3 class="font-bold text-lg text-slate-800">Credit Facility</h3>
            <button type="button" onclick="closeCreditModal()"
                class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-blue-50 p-4 rounded-lg flex items-start gap-3">
                <i class="fas fa-info-circle text-brand-blue mt-1"></i>
                <p class="text-sm text-blue-800">
                    We offer flexible credit plans to help you fly now and pay later.
                </p>
            </div>
            <ul class="space-y-3">
                <li class="flex items-center gap-3 text-sm text-slate-600">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>Pay in installments (up to 3 months)</span>
                </li>
                <li class="flex items-center gap-3 text-sm text-slate-600">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>No hidden fees or interest</span>
                </li>
                <li class="flex items-center gap-3 text-sm text-slate-600">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>Instant approval for eligible customers</span>
                </li>
            </ul>
            <button type="button" onclick="closeCreditModal()"
                class="w-full bg-brand-blue hover:bg-brand-blueHover text-white py-3 rounded-lg font-bold shadow-lg shadow-brand-blue/20 transition-all mt-4">
                Understood
            </button>
        </div>
    </div>
</div>

@section('js')
    <script>
        const flights = @json($flights);
        const routeModel = parseInt("{{ $routeModel }}"); // 0=OneWay, 1=RoundTrip, 2=MultiCity

        const PAGE_SIZE = 10;
        let currentSort = 'cheapest';
        let visibleCount = PAGE_SIZE;
        let currentFilteredFlights = [];
        let container = document.getElementById('flight-list');
        const getPrice = (flight) => parseFloat(flight.rawPrice);

        function applyFiltersAndSort() {
            // ... (Keep existing filter logic: stops, airlines, sliders) ...
            const stopCheckboxes = document.querySelectorAll('.filter-stop:checked');
            const selectedStops = Array.from(stopCheckboxes).map(cb => cb.value);
            const airlineCheckboxes = document.querySelectorAll('.filter-airline:checked');
            const selectedAirlines = Array.from(airlineCheckboxes).map(cb => cb.value);
            const priceSlider = document.getElementById('price-slider');
            const maxPrice = priceSlider ? parseInt(priceSlider.value) : 100000000;

            let filtered = flights.filter(flight => {
                let firstLegStops = flight.itineraries[0].stops;
                if (selectedStops.length > 0 && !selectedStops.includes(firstLegStops)) return false;
                if (selectedAirlines.length > 0 && !selectedAirlines.includes(flight.airline)) return false;
                if (getPrice(flight) > maxPrice) return false;
                return true;
            });

            if (currentSort === 'cheapest') {
                filtered.sort((a, b) => getPrice(a) - getPrice(b));
            } else if (currentSort === 'fastest') {
                filtered.sort((a, b) => a.totalDuration - b.totalDuration);
            }

            visibleCount = PAGE_SIZE;
            currentFilteredFlights = filtered;
            renderFlights(filtered);
        }

        function filterBySingleAirline(airlineName) {
            // Filter flights to show only the selected airline
            let filtered = flights.filter(flight => flight.airline === airlineName);

            // Sort by price (cheapest first)
            filtered.sort((a, b) => getPrice(a) - getPrice(b));

            visibleCount = PAGE_SIZE;
            currentFilteredFlights = filtered;
            renderFlights(filtered);
        }

        function setSort(type) {
            currentSort = type;
            document.querySelectorAll('.sort-btn').forEach(btn => {
                btn.classList.remove('bg-brand-blue', 'text-white');
                btn.classList.add('hover:text-brand-blue');
            });
            const activeBtn = document.getElementById(`sort-${type}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-brand-blue', 'text-white');
                activeBtn.classList.remove('hover:text-brand-blue');
            }
            applyFiltersAndSort();
        }

        function toggleSearchForm() {
            const modifyBar = document.getElementById('modify-bar');
            const searchForm = document.getElementById('search-form');

            if (modifyBar && searchForm) {
                // Toggle visibility
                modifyBar.classList.toggle('hidden');
                searchForm.classList.toggle('hidden');

                // Update button text based on current state
                const button = document.getElementById('modify-search-btn');
                if (button) {
                    if (searchForm.classList.contains('hidden')) {
                        button.textContent = 'Modify Search';
                    } else {
                        button.textContent = 'Hide Search';
                    }
                }
            }
        }


        function renderFlights(data) {
            container.innerHTML = '';
            const loadMoreWrapper = document.getElementById('load-more-wrapper');
            const flightCountInfo = document.getElementById('flight-count-info');

            if (data.length === 0) {
                container.innerHTML = '<div class="p-6 text-center text-slate-500 bg-white rounded-lg shadow">No flights found matching your filters.</div>';
                if (loadMoreWrapper) loadMoreWrapper.classList.add('hidden');
                return;
            }

            const toShow = data.slice(0, visibleCount);

            toShow.forEach(flight => {
                let displayBag = '1 bag x 23kg';
                if (flight.bags) {
                    if (flight.bags.toLowerCase().includes('bag')) {
                        const qty = parseInt(flight.bags) || 1;
                        displayBag = `${qty} bag${qty > 1 ? 's' : ''} x 23kg`;
                    } else if (flight.bags.toLowerCase().includes('kg')) {
                        const weight = parseInt(flight.bags) || 23;
                        displayBag = `1 bag x ${weight}kg`;
                    }
                }

                // Loop through ALL itineraries provided by the controller
                let legsHtml = flight.itineraries.map((leg, index) => {

                    let legLabel = '';

                    // STRICT LABEL LOGIC BASED ON ROUTE MODEL
                    if (routeModel === 2) {
                        // Multi-City: Always Flight 1, Flight 2, Flight 3...
                        legLabel = `Flight ${index + 1}`;
                    } else if (routeModel === 1) {
                        // Round Trip: Outbound / Return
                        legLabel = (index === 0) ? 'Outbound' : 'Return';
                    } else {
                        // One Way (or default)
                        legLabel = 'Outbound';
                    }

                    return `
                                                                                                                <div class="flex flex-col md:flex-row justify-between items-center gap-4 ${index > 0 ? 'mt-4 pt-4 border-t border-slate-100' : ''}">
                                                                                                                    <div class="flex items-center gap-4 w-full md:w-1/4">
                                                                                                                        <img src="https://pics.avs.io/200/60/${leg.airlineCode}.png" class="h-6 object-contain" alt="${leg.airlineName}">
                                                                                                                        <div>
                                                                                                                            <div class="text-[10px] uppercase font-bold text-slate-400">
                                                                                                                                ${legLabel}
                                                                                                                            </div>
                                                                                                                            <h3 class="font-bold text-sm text-slate-800">${leg.airlineName}</h3>
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                    <div class="flex flex-1 justify-between items-center text-center w-full gap-4 md:gap-0">
                                                                                                                        <div class="text-left min-w-[80px]">
                                                                                                                            <div class="text-lg font-bold text-slate-800 leading-tight">${leg.depTime}</div>
                                                                                                                            <div class="text-xs font-semibold text-slate-600">${leg.depCity}</div>
                                                                                                                            <div class="text-[10px] text-brand-blue">${leg.depDate}</div>
                                                                                                                        </div>

                                                                                                                        <div class="flex flex-col items-center flex-1 px-4">
                                                                                                                            <div class="text-[10px] text-brand-blue mb-1">${leg.duration}</div>
                                                                                                                            <div class="w-full h-px bg-slate-300 relative flex items-center justify-center">
                                                                                                                                <div class="w-1.5 h-1.5 rounded-full bg-slate-300 absolute left-0"></div>
                                                                                                                                <i class="fas fa-plane text-slate-300 text-[10px] transform rotate-90"></i>
                                                                                                                                <div class="w-1.5 h-1.5 rounded-full bg-slate-300 absolute right-0"></div>
                                                                                                                            </div>
                                                                                                                            <div class="text-[10px] font-medium text-brand-orange mt-1">
                                                                                                                                ${leg.stops} ${leg.stopCity ? `(${leg.stopCity})` : ''}
                                                                                                                            </div>
                                                                                                                        </div>

                                                                                                                        <div class="text-right min-w-[80px]">
                                                                                                                            <div class="text-lg font-bold text-slate-800 leading-tight">${leg.arrTime}</div>
                                                                                                                            <div class="text-xs font-semibold text-slate-600">${leg.arrCity}</div>
                                                                                                                            <div class="text-[10px] text-brand-blue">${leg.arrDate}</div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                `;
                }).join('');

                const card = `
                                                                                                    <div class="bg-white rounded-lg p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden flex flex-col md:flex-row gap-6">
                                                                                                        <div class="flex-1 flex flex-col justify-center">
                                                                                                            ${legsHtml}
                                                                                                        </div>
                                                                                                        <div class="w-full md:w-1/5 flex flex-row md:flex-col justify-between items-center md:items-center gap-2 md:pl-6 md:border-l border-slate-100 min-h-full">
                                                                                                            <div class="text-right md:text-center mt-auto mb-auto w-full">
                                                                                                                <!-- Pill decoration above price -->
                                                                                                                <div class="w-8 h-1.5 bg-slate-900 rounded-full mb-2 mx-auto hidden md:block"></div>

                                                                                                                <div class="text-2xl font-bold text-slate-900">
                                                                                                                    <span>{{ config('app.currency_symbol') }}</span>${flight.price}
                                                                                                                </div>

                                                                                                                <form action="{{ route('api.offer.verify') }}" method="POST" class="w-full mt-3">
                                                                                                                    @csrf
                                                                                                                    <input type="hidden" name="allOffer" value="${encodeURIComponent(JSON.stringify(flight.allOffer))}">

                                                                                                                    <button type="submit" class="bg-brand-blue hover:bg-brand-blueHover text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-lg shadow-brand-blue/20 w-full block">
                                                                                                                    Book Now
                                                                                                                </button>
                                                                                                            </form>

                                                                                                            <button type="button" onclick="openCreditModal()" class="text-brand-blue hover:text-blue-700 text-xs font-bold mt-3 underline underline-offset-2 cursor-pointer transition-colors">
                                                                                                                Credit Facility Available
                                                                                                            </button>

                                                                                                                <div class="mt-3 text-center">
                                                                                                                    <div class="text-[10px] text-slate-500">Offer expires in:</div>
                                                                                                                    <div class="text-xs font-bold text-brand-orange countdown-timer" data-minutes="${Math.floor(Math.random() * 30) + 15}">
                                                                                                                        --m --s
                                                                                                                    </div>
                                                                                                                </div>

                                                                                                                <div class="flex items-center justify-center gap-4 mt-3 font-bold text-xs text-slate-600" style="background-color: #d4d4e0ff; padding: 2px; border-radius: 10px;">
                                                                                                                    <div class="flex items-center gap-1" title="Checked Baggage">
                                                                                                                        <i class="fas fa-briefcase text-brand-orange"></i>
                                                                                                                        <span>${displayBag}</span>
                                                                                                                    </div>
                                                                                                                    <div class="flex items-center gap-1" title="Cabin Baggage">
                                                                                                                        <i class="fas fa-suitcase-rolling text-brand-orange"></i>
                                                                                                                        <span>${flight.cabinBag || '7kg'}</span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                `;
                container.innerHTML += card;
            });

            // Update load-more button visibility and flight count
            if (loadMoreWrapper) {
                const remaining = data.length - visibleCount;
                if (remaining > 0) {
                    loadMoreWrapper.classList.remove('hidden');
                    const loadMoreBtn = document.getElementById('load-more-btn');
                    loadMoreBtn.classList.remove('hidden');
                    const nextBatch = Math.min(remaining, PAGE_SIZE);
                    flightCountInfo.textContent = `Showing ${toShow.length} of ${data.length} flights`;
                    loadMoreBtn.innerHTML = `Load ${nextBatch} more flight${nextBatch > 1 ? 's' : ''} <i class="fas fa-chevron-down ml-2"></i>`;
                } else {
                    loadMoreWrapper.classList.remove('hidden');
                    flightCountInfo.textContent = `Showing all ${data.length} flight${data.length > 1 ? 's' : ''}`;
                    document.getElementById('load-more-btn').classList.add('hidden');
                }
            }
        }

        function loadMoreFlights() {
            visibleCount += PAGE_SIZE;
            renderFlights(currentFilteredFlights);

            // Smooth scroll to the newly loaded flights
            setTimeout(() => {
                const allCards = container.querySelectorAll(':scope > div');
                const scrollTarget = allCards[visibleCount - PAGE_SIZE] || allCards[allCards.length - 1];
                if (scrollTarget) {
                    scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 100);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const filterInputs = document.querySelectorAll('.filter-stop, .filter-airline');
            filterInputs.forEach(input => input.addEventListener('change', applyFiltersAndSort));
            const sliders = document.querySelectorAll('#price-slider, #duration-slider');
            sliders.forEach(input => input.addEventListener('input', applyFiltersAndSort));

            // "All Airlines" master checkbox logic
            const allAirlinesCheckbox = document.getElementById('filter-airline-all');
            const airlineFilterCheckboxes = document.querySelectorAll('.filter-airline');

            if (allAirlinesCheckbox) {
                allAirlinesCheckbox.addEventListener('change', function () {
                    airlineFilterCheckboxes.forEach(cb => {
                        cb.checked = allAirlinesCheckbox.checked;
                    });
                    applyFiltersAndSort();
                });

                // Sync "All Airlines" when individual checkboxes change
                airlineFilterCheckboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        const allChecked = Array.from(airlineFilterCheckboxes).every(c => c.checked);
                        allAirlinesCheckbox.checked = allChecked;
                    });
                });
            }

            applyFiltersAndSort();
        });

        function scrollAirlines(direction) {
            const container = document.getElementById('airline-scroll-container');
            const content = document.getElementById('airline-scroll-content');
            const scrollAmount = 300; // pixels to scroll per click

            if (direction === 'left') {
                container.scrollLeft -= scrollAmount;
            } else {
                container.scrollLeft += scrollAmount;
            }
        }

        // Credit Modal Logic
        function openCreditModal() {
            const modal = document.getElementById('credit-facility-modal');
            const content = document.getElementById('credit-modal-content');
            if (modal && content) {
                modal.classList.remove('hidden');
                // Small delay to allow display:block to apply before opacity transition
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }, 10);
            }
        }

        function closeCreditModal() {
            const modal = document.getElementById('credit-facility-modal');
            const content = document.getElementById('credit-modal-content');
            if (modal && content) {
                modal.classList.add('opacity-0');
                content.classList.remove('scale-100');
                content.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300); // Match transition duration
            }
        }

        // Countdown Timer Initialization
        function initCountdownTimers() {
            const timers = document.querySelectorAll('.countdown-timer');
            timers.forEach(timer => {
                const minutes = parseInt(timer.dataset.minutes) || 30;
                let totalSeconds = minutes * 60;

                const updateTimer = () => {
                    if (totalSeconds <= 0) {
                        timer.textContent = 'Expired';
                        timer.classList.add('text-red-500');
                        timer.classList.remove('text-brand-orange');
                        return;
                    }

                    const mins = Math.floor(totalSeconds / 60);
                    const secs = totalSeconds % 60;
                    timer.textContent = `${mins}m ${secs.toString().padStart(2, '0')}s`;
                    totalSeconds--;
                };

                updateTimer(); // Initial call
                setInterval(updateTimer, 1000);
            });
        }

        // Initialize timers after rendering flights
        const originalRenderFlights = renderFlights;
        renderFlights = function (data) {
            originalRenderFlights(data);
            initCountdownTimers();
        };
    </script>
@endsection