<section id="booking-section" class="px-4">
    <div class="container mx-auto max-w-6xl">
        <div class="md:hidden bg-[#163A66] rounded-t-lg shadow-lg p-4 flex justify-between items-center cursor-pointer"
            id="accordion-header">
            <h3 class="font-bold text-lg text-white">Book a Flight</h3>
            <i id="accordion-icon" class="fas fa-chevron-down transform transition-transform text-white"></i>
        </div>
        <form action="{{ route('search') }}" method="POST">
            @csrf
            <div id="booking-bar"
                class="bg-[#163A66] p-6 rounded-b-lg md:rounded-lg shadow-lg accordion-content md:!max-h-none md:overflow-visible border border-white/10">
                <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
                    <div class="relative w-full md:w-auto">
                        <select id="trip-type" name="routeModel"
                            class="w-full appearance-none bg-white/10 border border-white/20 rounded-lg py-3 pl-4 pr-10 text-white focus:outline-none focus:ring-2 focus:ring-white/50 [&>option]:text-blue-600">
                            <option value="1">Round Trip</option>
                            <option value="0">One Way</option>
                            <option value="2">Multi-City</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-white/60 pointer-events-none"></i>
                    </div>

                    <div class="relative w-full md:w-auto">
                        <input type="hidden" name="travelers[numberOfAdults]" id="input-adults" value="1">
                        <input type="hidden" name="travelers[numberOfChildren]" id="input-children" value="0">
                        <input type="hidden" name="travelers[numberOfInfants]" id="input-infants" value="0">

                        <button type="button" id="passenger-btn"
                            class="w-full text-left bg-white/10 border border-white/20 rounded-lg py-3 px-4 text-white focus:outline-none focus:ring-2 focus:ring-white/50 flex items-center justify-between">
                            <span><i class="fas fa-user mr-2 text-white/80"></i><span id="passenger-count-text">1
                                    Passenger</span></span>
                            <i class="fas fa-chevron-down text-white/60"></i>
                        </button>

                        <div id="passenger-dropdown"
                            class="hidden absolute top-full mt-2 w-72 bg-white dark:bg-slate-800 rounded-lg shadow-xl p-4 z-50 border dark:border-slate-700">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">Adults</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Ages 12+</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-type="adults" data-action="decrease"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600">-</button>
                                        <span id="adults-count" class="w-8 text-center font-bold">1</span>
                                        <button type="button" data-type="adults" data-action="increase"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600">+</button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">Children</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Ages 2-11</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-type="children" data-action="decrease"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600">-</button>
                                        <span id="children-count" class="w-8 text-center font-bold">0</span>
                                        <button type="button" data-type="children" data-action="increase"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600">+</button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium">Infants</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Under 2</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-type="infants" data-action="decrease"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600">-</button>
                                        <span id="infants-count" class="w-8 text-center font-bold">0</span>
                                        <button type="button" data-type="infants" data-action="increase"
                                            class="passenger-control w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600">+</button>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-4 border-slate-200 dark:border-slate-600">
                            <button type="button" id="passenger-done-btn"
                                class="w-full bg-brand-blue hover:bg-sky-700 text-white font-bold py-2 px-4 rounded-lg">Done</button>
                        </div>
                    </div>

                    <div class="relative w-full md:w-auto">
                        <select id="cabin-class" name="flightClass"
                            class="w-full appearance-none bg-white/10 border border-white/20 rounded-lg py-3 pl-4 pr-10 text-white focus:outline-none focus:ring-2 focus:ring-white/50 [&>option]:text-blue-600">
                            <option value="ECONOMY">Economy</option>
                            <option value="PREMIUM_ECONOMY">Premium Economy</option>
                            <option value="BUSINESS">Business</option>
                            <option value="FIRST">First</option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-white/60 pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <div id="single-flight-form" class="grid grid-cols-1 md:grid-cols-10 gap-4 items-end">
                        <div class="col-span-12 md:col-span-3 relative autocomplete">
                            <label for="origin" class="block text-sm font-medium text-white/80 mb-1">Where
                                From?</label>
                            <div class="relative">
                                <i
                                    class="fas fa-plane-departure absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
                                <input type="text" id="origin" name="originLocationCode"
                                    placeholder="Where From? (e.g. JFK)" value="DFW"
                                    class="w-full bg-white/10 border border-white/20 rounded-lg py-3 pl-10 pr-4 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-3 relative autocomplete">
                            <label for="destination" class="block text-sm font-medium text-white/80 mb-1">Going
                                To?</label>
                            <div class="relative">
                                <i
                                    class="fas fa-plane-arrival absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
                                <input type="text" id="destination" name="originDestinationCode" placeholder="Going To?"
                                    value="SEZ"
                                    class="w-full bg-white/10 border border-white/20 rounded-lg py-3 pl-10 pr-4 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-2">
                            <label for="departure-date"
                                class="block text-sm font-medium text-white/80 mb-1">Departure</label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-white/60 pointer-events-none"></i>
                                <input type="text" id="departure-date" name="departureDate" placeholder="Select date"
                                    class="w-full bg-white/10 border border-white/20 rounded-lg py-3 pl-10 pr-4 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                            </div>
                        </div>
                        <div id="return-date-wrapper" class="col-span-12 md:col-span-2">
                            <label for="return-date"
                                class="block text-sm font-medium text-white/80 mb-1">Returning</label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-white/60 pointer-events-none"></i>
                                <input type="text" id="return-date" name="returnDate" placeholder="Select date"
                                    class="w-full bg-white/10 border border-white/20 rounded-lg py-3 pl-10 pr-4 text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                            </div>
                        </div>
                    </div>

                    <div id="multi-city-form" class="hidden">
                        <div id="multi-city-rows" class="space-y-4">
                        </div>
                    </div>
                </div>

                <div
                    class="mt-4 pt-4 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center flex-wrap gap-4 md:gap-6 w-full md:w-auto">
                        <label id="flexible-dates-wrapper" class="flex items-center cursor-pointer">
                            <input type="checkbox" name="dateWindow"
                                class="h-4 w-4 rounded border-white/30 text-blue focus:ring-white/50">
                            <span class="ml-2 text-sm text-white/80">+/- 3 days</span>
                        </label>
                        <button type="button" id="add-flight-btn"
                            class="hidden text-sm font-medium text-white/80 hover:text-white">
                            <i class="fas fa-plus mr-2"></i>Add flight
                        </button>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="directFlightOnly"
                                class="h-4 w-4 rounded border-white/30 text-blue focus:ring-white/50">
                            <span class="ml-2 text-sm text-white/80">Direct flight</span>
                        </label>
                    </div>
                    <div class="w-full md:w-auto">
                        <button
                            class="w-full md:w-auto bg-brand-blue hover:bg-brand-blueHover text-white font-bold py-3 px-6 rounded-lg transition-colors duration-300 shadow-lg shadow-brand-blue/20">
                            Let's Fly
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@include('common.front.search-overlay')
