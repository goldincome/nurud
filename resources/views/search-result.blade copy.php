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
                    <div class="hidden xl:flex flex-col">
                        <span class="text-white/60 text-xs uppercase">Cabin</span>
                        <span class="text-white font-bold">Economy</span>
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

        <div id="search-form" class="hidden">
            @include('common.front.booking-form')
        </div>
    </div>

    <main class="container mx-auto px-4 py-6">


        <div class="flex justify-between items-center mb-6">
            <h1 class="text-lg font-semibold text-slate-700">
                Flight Results from <span class="text-sky-600">{{ $origin }}</span> to <span
                    class="text-sky-600">{{ $destination }}</span>
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
                        <a href="#" onclick="resetFilters(event)"
                            class="text-xs text-brand-orange hover:underline">Clear Filters</a>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-semibold text-sm mb-3 text-slate-700">Stops</h4>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" value="Non-stop Flights"
                                    class="filter-stop rounded text-brand-blue focus:ring-brand-blue border-slate-300">
                                <span class="text-sm text-slate-600">Non-stop Flights</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" value="1 Stop" checked
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
                            @if (count($airlines) > 0)
                                @foreach ($airlines as $index => $airline)
                                    @if ($index !== 0)
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" value="{{ $airline['name'] }}" checked
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
                            <span id="price-display" class="text-xs font-bold text-brand-blue">₦5,000,000</span>
                        </div>
                        <input type="range" id="price-slider" min="1000000" max="5000000" step="100000" value="5000000"
                            class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-500 mt-2">
                            <span>₦1M</span>
                            <span>₦5M+</span>
                        </div>
                    </div>

                    <hr class="border-slate-100 mb-6">

                    <div class="mb-2">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold text-sm text-slate-700">Max Duration</h4>
                            <span id="duration-display" class="text-xs font-bold text-brand-blue">50h</span>
                        </div>
                        <input type="range" id="duration-slider" min="10" max="50" step="1"
                            value="50" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-500 mt-2">
                            <span>10h</span>
                            <span>50h+</span>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-9 space-y-4">

                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                    <div class="flex overflow-x-auto matrix-scroll divide-x divide-slate-100">
                        <div class="flex-none w-32 bg-slate-50 p-3 flex flex-col justify-center items-center text-center">
                            <span class="text-xs font-bold text-slate-500">All Airlines</span>
                            <span class="text-xs text-slate-400 mt-1">Best Price</span>
                        </div>

                        <div class="flex-none w-32 p-3 flex flex-col justify-center items-center text-center hover:bg-slate-50 cursor-pointer transition"
                            onclick="filterBySingleAirline('Turkish Airlines')">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e9/Turkish_Airlines_logo_2019.svg/1200px-Turkish_Airlines_logo_2019.svg.png"
                                class="h-6 object-contain mb-2" alt="TK">
                            <span class="text-xs font-bold text-slate-700">₦2,412,241</span>
                        </div>
                        <div class="flex-none w-32 p-3 flex flex-col justify-center items-center text-center bg-blue-50/50 border-b-2 border-brand-blue cursor-pointer"
                            onclick="filterBySingleAirline('Ethiopian Airlines')">
                            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/2/2c/Ethiopian_Airlines_Logo.svg/1200px-Ethiopian_Airlines_Logo.svg.png"
                                class="h-8 object-contain mb-2" alt="ET">
                            <span class="text-xs font-bold text-brand-blue">₦2,534,427</span>
                        </div>
                        <div class="flex-none w-32 p-3 flex flex-col justify-center items-center text-center hover:bg-slate-50 cursor-pointer transition"
                            onclick="filterBySingleAirline('Lufthansa')">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Lufthansa_Logo_2018.svg/2048px-Lufthansa_Logo_2018.svg.png"
                                class="h-5 object-contain mb-2" alt="LH">
                            <span class="text-xs font-bold text-slate-700">₦3,123,564</span>
                        </div>
                        <div class="flex-none w-32 p-3 flex flex-col justify-center items-center text-center hover:bg-slate-50 cursor-pointer transition"
                            onclick="filterBySingleAirline('United Airlines')">
                            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/e/e0/United_Airlines_Logo.svg/1200px-United_Airlines_Logo.svg.png"
                                class="h-6 object-contain mb-2" alt="UA">
                            <span class="text-xs font-bold text-slate-700">₦3,723,410</span>
                        </div>
                        <div class="flex-none w-32 p-3 flex flex-col justify-center items-center text-center hover:bg-slate-50 cursor-pointer transition"
                            onclick="filterBySingleAirline('Qatar Airways')">
                            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/9b/Qatar_Airways_Logo.svg/1200px-Qatar_Airways_Logo.svg.png"
                                class="h-6 object-contain mb-2" alt="QR">
                            <span class="text-xs font-bold text-slate-700">₦4,220,109</span>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-2 flex justify-between items-center text-xs text-brand-blue">
                        <button class="hover:underline"><i class="fas fa-chevron-left mr-1"></i> Show less
                            airlines</button>
                        <button class="hover:underline">Show more airlines <i
                                class="fas fa-chevron-right ml-1"></i></button>
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

                <div class="text-center pt-4">
                    <button
                        class="bg-brand-blue hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-transform hover:scale-105 flex items-center justify-center mx-auto">
                        Load more flights <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                </div>

            </div>
        </div>


    </main>

@endsection

@section('js')
<script>
    const flights = @json($flights);
    const routeModel = parseInt("{{ $routeModel }}"); // 0=OneWay, 1=RoundTrip, 2=MultiCity

    let currentSort = 'cheapest'; 
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
        
        renderFlights(filtered);
    }

    function setSort(type) {
        currentSort = type;
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.classList.remove('bg-brand-blue', 'text-white');
            btn.classList.add('hover:text-brand-blue');
        });
        const activeBtn = document.getElementById(`sort-${type}`);
        if(activeBtn) {
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

    function verifyFlightOffer(flightId, price, duration, airline, seats) {
        // Prepare the flight offer data for submission
        const offerData = {
            type: "flight-offer",
            id: flightId,
            routeModel: parseInt("{{ $routeModel }}"),
            source: "GDS",
            officeId: "607C19C3",
            amaClientRef: "U122914D04210302E41497CA11DA36B00F161D0",
            clustersExpanded: false,
            durationInMinutes: parseInt(duration) || 0,
            instantTicketingRequired: false,
            nonHomogeneous: false,
            oneWay: (parseInt("{{ $routeModel }}") === 0),
            lastTicketingDate: "2025-04-26",
            numberOfBookableSeats: parseInt(seats) || 9,
            itineraries: [],
            unconvertedPrice: price || "0",
            price: { total: price || "0" },
            priceBreakdown: {},
            pricingOptions: {},
            validatingAirlineCodes: airline ? [airline] : [],
            travelerPricings: []
        };

        // Create a form element
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/api/offer/verify';

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.getAttribute('content');
            form.appendChild(tokenInput);
        }

        // Add flight offer data as hidden input
        const dataInput = document.createElement('input');
        dataInput.type = 'hidden';
        dataInput.name = 'flightOffer';
        dataInput.value = JSON.stringify(offerData);
        form.appendChild(dataInput);

        // Add flight ID as hidden input
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'flightId';
        idInput.value = flightId;
        form.appendChild(idInput);

        // Add route model as hidden input
        const routeModelInput = document.createElement('input');
        routeModelInput.type = 'hidden';
        routeModelInput.name = 'routeModel';
        routeModelInput.value = "{{ $routeModel }}";
        form.appendChild(routeModelInput);

        // Add origin and destination as hidden inputs
        const originInput = document.createElement('input');
        originInput.type = 'hidden';
        originInput.name = 'origin';
        originInput.value = "{{ $origin }}";
        form.appendChild(originInput);

        const destinationInput = document.createElement('input');
        destinationInput.type = 'hidden';
        destinationInput.name = 'destination';
        destinationInput.value = "{{ $destination }}";
        form.appendChild(destinationInput);

        // Add trip date as hidden input
        const tripDateInput = document.createElement('input');
        tripDateInput.type = 'hidden';
        tripDateInput.name = 'tripDate';
        tripDateInput.value = "{{ $tripDate }}";
        form.appendChild(tripDateInput);

        // Add the form to the document and submit it
        document.body.appendChild(form);
        form.submit();
    }

    function renderFlights(data) {
        container.innerHTML = '';
        
        if (data.length === 0) {
            container.innerHTML = '<div class="p-6 text-center text-slate-500 bg-white rounded-lg shadow">No flights found matching your filters.</div>';
            return;
        }

        data.forEach(flight => {
            
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
                         <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100 p-1">
                             <span class="font-bold text-[10px] text-slate-600">${leg.airlineCode}</span>
                        </div>
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
                            <div class="text-[10px] text-slate-400">${leg.depDate}</div>
                        </div>

                        <div class="flex flex-col items-center flex-1 px-4">
                            <div class="text-[10px] text-slate-400 mb-1">${leg.duration}</div>
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
                            <div class="text-[10px] text-slate-400">${leg.arrDate}</div>
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
                        <div class="text-right md:text-center mt-auto mb-auto">
                            <div class="text-2xl font-bold text-brand-blue">
                                <span class="text-lg align-top">₦</span>${flight.price}
                            </div>
                            <div class="text-xs text-slate-400 hidden md:block mb-2">Total Price</div>
                            <div class="text-[10px] bg-slate-100 text-slate-500 rounded px-2 py-1 inline-block">
                                ${flight.bags}
                            </div>
                        </div>
                        <button class="bg-brand-blue hover:bg-brand-blueHover text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-colors shadow-lg shadow-brand-blue/20 w-auto md:w-full mt-auto"
                            onclick="verifyFlightOffer('${flight.id}', '${flight.rawPrice || flight.price}', '${flight.totalDuration || 0}', '${flight.airline || ''}', '${flight.numberOfBookableSeats || 9}')">
                            Book Now <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>
            `;
            container.innerHTML += card;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const filterInputs = document.querySelectorAll('.filter-stop, .filter-airline');
        filterInputs.forEach(input => input.addEventListener('change', applyFiltersAndSort));
        const sliders = document.querySelectorAll('#price-slider, #duration-slider');
        sliders.forEach(input => input.addEventListener('input', applyFiltersAndSort));
        applyFiltersAndSort();
    });
</script>
@endsection
