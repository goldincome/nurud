@extends('layouts.front')

@section('content')
 <!-- Progress Bar Section -->
    <div class="bg-brand-blue py-8">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="relative flex items-center justify-between w-full">
                <!-- Line background -->
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-blue-900 z-0"></div>
                <!-- Progress Line -->
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-1/3 h-1 bg-brand-orange z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center step-completed">
                    <div class="step-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-md">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-xs text-brand-orange font-medium">Search flight</span>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center step-active">
                    <div class="step-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-md">
                        2
                    </div>
                    <span class="text-xs text-white font-medium">Traveler Details</span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center step-inactive">
                    <div class="step-circle w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-sm mb-2">
                        3
                    </div>
                    <span class="text-xs text-slate-300 font-medium">Make payment</span>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 flex flex-col items-center step-inactive">
                    <div class="step-circle w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-sm mb-2">
                        4
                    </div>
                    <span class="text-xs text-slate-300 font-medium">Confirmation</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        
        <!-- Info Banner -->
        <div class="flex justify-center items-center mb-8 text-sm font-medium text-brand-blue">
            <span class="mr-2">👍</span>
            <span>Good job!, few more steps to booking your itinerary at the best price</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Flight Info & Forms -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Selected Flight Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    @if(isset($flightData['itineraries']) && count($flightData['itineraries']) > 0)
                        @foreach($flightData['itineraries'] as $itineraryIndex => $itinerary)
                            @if($itineraryIndex > 0) <div class="border-t border-dashed border-slate-200 my-6"></div> @endif
                            
                            <!-- Flight Segment -->
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
                                <div class="flex items-center gap-4 mb-4 md:mb-0">
                                    @if(isset($itinerary['segments'][0]['carrier']['iataCode']))
                                        <img src="https://pics.avs.io/200/60/{{ $itinerary['segments'][0]['carrier']['iataCode'] }}.png" class="h-8 object-contain" alt="{{ $itinerary['segments'][0]['carrier']['name'] }}">
                                    @endif
                                    <div>
                                        @if(isset($itinerary['segments'][0]['segmentDeparture']['at']))
                                            @php
                                                $depTime = \Carbon\Carbon::parse($itinerary['segments'][0]['segmentDeparture']['at']);
                                                $depDate = $depTime->format('D, d M');
                                                $depTimeFormatted = $depTime->format('H:i');
                                            @endphp
                                            <div class="text-xl font-bold text-brand-textDark">{{ $depTimeFormatted }}</div>
                                            <div class="text-xs text-slate-500">{{ $itinerary['segments'][0]['segmentDeparture']['airport']['city'] }}</div>
                                            <div class="text-xs text-slate-500">{{ $depDate }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-1 px-8 flex flex-col items-center">
                                    <span class="text-xs text-slate-400 mb-1">Duration: {{ $itinerary['duration'] }}</span>
                                    <div class="w-full flex items-center">
                                        <div class="h-[1px] bg-slate-300 flex-1 relative">
                                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-brand-blue"></div>
                                        </div>
                                        <i class="fas fa-plane text-slate-300 ml-2 transform rotate-90"></i>
                                    </div>
                                    @if(isset($itinerary['segments']) && count($itinerary['segments']) > 1)
                                        <span class="text-xs text-brand-blue mt-1">{{ count($itinerary['segments']) - 1 }} Stop</span>
                                    @else
                                        <span class="text-xs text-brand-blue mt-1">Non-stop</span>
                                    @endif
                                    @if(isset($itinerary['segments'][0]['cabin']))
                                        <span class="text-xs text-sky-500 cursor-pointer hover:underline">{{ $itinerary['segments'][0]['cabin'] }} ({{ $itinerary['segments'][0]['class'] }})</span>
                                    @endif
                                </div>

                                <div class="text-right">
                                    @if(isset($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at']))
                                        @php
                                            $arrTime = \Carbon\Carbon::parse($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at']);
                                            $arrDate = $arrTime->format('D, d M');
                                            $arrTimeFormatted = $arrTime->format('H:i');
                                        @endphp
                                        <div class="text-xl font-bold text-brand-textDark">{{ $arrTimeFormatted }}</div>
                                        <div class="text-xs text-slate-500">{{ $itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['airport']['city'] }}</div>
                                        <div class="text-xs text-slate-500">{{ $arrDate }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Price Highlight inside card -->
                    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center md:hidden">
                         <span class="text-sm font-semibold text-slate-500">Total Price:</span>
                         <span class="text-xl font-bold text-brand-textDark">
                             @if(isset($flightData['verifiedPrice']['total']))
                                 ₦{{ number_format($flightData['verifiedPrice']['total']) }}
                             @else
                                 ₦0
                             @endif
                         </span>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-brand-textDark pl-1">Who's Travelling?</h2>

                <!-- Passenger Forms -->
                @if(isset($flightData['travelerPricings']))
                    @php
                        $adultCount = 0;
                        $childCount = 0;
                        $infantCount = 0;
                        
                        foreach($flightData['travelerPricings'] as $pricing) {
                            if($pricing['travelerType'] === 'ADULT') {
                                $adultCount++;
                            } elseif($pricing['travelerType'] === 'CHILD') {
                                $childCount++;
                            } elseif($pricing['travelerType'] === 'INFANT') {
                                $infantCount++;
                            }
                        }
                    @endphp

                    <!-- Adult Passengers -->
                    @for($i = 1; $i <= $adultCount; $i++)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                            <h3 class="font-bold text-lg mb-1">Passenger {{ $i }} (Adult)</h3>
                            <p class="text-xs text-slate-500 mb-4">Traveller names must match government-issued photo ID.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Title</label>
                                    <select name="passengers[adult_{{ $i }}][title]" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                    </select>
                                </div>
                                <!-- First Name -->
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">First Name</label>
                                    <input type="text" name="passengers[adult_{{ $i }}][firstName]" placeholder="Enter first name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                                <!-- Middle Name -->
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Middle Name</label>
                                    <input type="text" name="passengers[adult_{{ $i }}][middleName]" placeholder="Enter middle name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                                <!-- Surname -->
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Surname</label>
                                    <input type="text" name="passengers[adult_{{ $i }}][surname]" placeholder="Enter surname" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                            </div>
                        </div>
                    @endfor

                    <!-- Child Passengers -->
                    @for($i = 1; $i <= $childCount; $i++)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                            <h3 class="font-bold text-lg mb-1">Passenger {{ $i }} (Child)</h3>
                            <p class="text-xs text-slate-500 mb-4">Traveller names must match government-issued photo ID.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Title</label>
                                    <select name="passengers[child_{{ $i }}][title]" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                        <option value="Master">Master</option>
                                        <option value="Miss">Miss</option>
                                    </select>
                                </div>
                                <!-- First Name -->
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">First Name</label>
                                    <input type="text" name="passengers[child_{{ $i }}][firstName]" placeholder="Enter first name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                                <!-- Middle Name -->
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Middle Name</label>
                                    <input type="text" name="passengers[child_{{ $i }}][middleName]" placeholder="Enter middle name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                                <!-- Surname -->
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Surname</label>
                                    <input type="text" name="passengers[child_{{ $i }}][surname]" placeholder="Enter surname" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                            </div>
                        </div>
                    @endfor

                    <!-- Infant Passengers -->
                    @for($i = 1; $i <= $infantCount; $i++)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                            <h3 class="font-bold text-lg mb-1">Passenger {{ $i }} (Infant)</h3>
                            <p class="text-xs text-slate-500 mb-4">Traveller names must match government-issued photo ID.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Title</label>
                                    <select name="passengers[infant_{{ $i }}][title]" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                        <option value="Master">Master</option>
                                        <option value="Miss">Miss</option>
                                    </select>
                                </div>
                                <!-- First Name -->
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">First Name</label>
                                    <input type="text" name="passengers[infant_{{ $i }}][firstName]" placeholder="Enter first name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                                <!-- Middle Name -->
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Middle Name</label>
                                    <input type="text" name="passengers[infant_{{ $i }}][middleName]" placeholder="Enter middle name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                                <!-- Surname -->
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Surname</label>
                                    <input type="text" name="passengers[infant_{{ $i }}][surname]" placeholder="Enter surname" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                </div>
                            </div>
                        </div>
                    @endfor
                @else
                    <!-- Fallback for when travelerPricings is not available -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-lg mb-1">Passenger 1 (Adult)</h3>
                        <p class="text-xs text-slate-500 mb-4">Traveller names must match government-issued photo ID.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Title</label>
                                <select class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                    <option>Mr</option>
                                    <option>Mrs</option>
                                    <option>Ms</option>
                                </select>
                            </div>
                            <!-- First Name -->
                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">First Name</label>
                                <input type="text" placeholder="Enter first name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                            </div>
                            <!-- Middle Name -->
                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Middle Name</label>
                                <input type="text" placeholder="Enter middle name" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                            </div>
                            <!-- Surname -->
                            <div class="md:col-span-4">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Surname</label>
                                <input type="text" placeholder="Enter surname" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contact Details Form -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-lg mb-4">Contact Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Email Address</label>
                            <input type="email" placeholder="Enter your email" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                        </div>
                        <div class="flex gap-2">
                            <div class="w-1/3">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Dialing Code</label>
                                <select class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                    <option>Nigeria (+234)</option>
                                    <option>USA (+1)</option>
                                    <option>UK (+44)</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Phone number</label>
                                <input type="tel" placeholder="Enter phone number" class="w-full bg-slate-100 border border-slate-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Terms & Continue -->
                <div class="flex flex-col md:flex-row justify-between items-center mt-4 gap-4">
                    <p class="text-xs text-slate-500">
                        By clicking continue, you are agreeing to the <a href="#" class="text-brand-blue underline">terms & conditions</a>
                    </p>
                    <button class="bg-brand-orange hover:bg-brand-orangeHover text-white font-bold py-3 px-10 rounded-full shadow-md transition-colors w-full md:w-auto">
                        Continue
                    </button>
                </div>

            </div>

            <!-- Right Column: Sidebar -->
            <aside class="lg:col-span-1 space-y-6">
                
                <!-- Booking Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                    <!-- Image Header -->
                    <div class="h-32 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1501594907352-004547f7dd2e?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Destination">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-brand-textDark mb-1">Booking Summary</h3>
                        <div class="w-10 h-1 bg-brand-orange mb-4"></div>

                        @if(isset($flightData['itineraries']) && count($flightData['itineraries']) > 0)
                            @foreach($flightData['itineraries'] as $itineraryIndex => $itinerary)
                                @if($itineraryIndex > 0) <div class="border-t border-dashed border-slate-200 my-4"></div> @endif
                                
                                <div class="mb-6">
                                    @if(isset($itinerary['segments'][0]['segmentDeparture']['airport']['city']) && 
                                        isset($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['airport']['city']))
                                        <h4 class="font-bold text-md text-brand-textDark">
                                            {{ $itinerary['segments'][0]['segmentDeparture']['airport']['city'] }} - 
                                            {{ $itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['airport']['city'] }}
                                        </h4>
                                    @endif
                                    @if(isset($itinerary['segments'][0]['segmentDeparture']['at']) && 
                                        isset($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at']))
                                        @php
                                            $startDate = \Carbon\Carbon::parse($itinerary['segments'][0]['segmentDeparture']['at'])->format('M d');
                                            $endDate = \Carbon\Carbon::parse($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at'])->format('M d');
                                        @endphp
                                        <p class="text-xs text-slate-500">{{ $startDate }} - {{ $endDate }}</p>
                                    @endif
                                </div>
                                
                                <div class="mb-4">
                                    <span class="bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">
                                        {{ $itinerary['itineraryTitle'] }}
                                    </span>
                                    <div class="flex justify-between mt-2">
                                        <div>
                                            @if(isset($itinerary['segments'][0]['segmentDeparture']['at']))
                                                @php
                                                    $depTime = \Carbon\Carbon::parse($itinerary['segments'][0]['segmentDeparture']['at']);
                                                    $depDate = $depTime->format('D, M d');
                                                    $depTimeFormatted = $depTime->format('H:i');
                                                @endphp
                                                <div class="font-bold text-sm text-brand-textDark">{{ $depDate }}</div>
                                                <div class="text-xs text-slate-500">{{ $depTimeFormatted }}</div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            @if(isset($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at']))
                                                @php
                                                    $arrTime = \Carbon\Carbon::parse($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at']);
                                                    $arrDate = $arrTime->format('D, M d');
                                                    $arrTimeFormatted = $arrTime->format('H:i');
                                                @endphp
                                                <div class="font-bold text-sm text-brand-textDark">{{ $arrDate }}</div>
                                                <div class="text-xs text-slate-500">{{ $arrTimeFormatted }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- Route Line -->
                        <div class="border-t border-dashed border-slate-200 my-4"></div>

                        <div class="mb-2">
                            <h4 class="font-bold text-md text-brand-textDark mb-3">Flight Base Fare</h4>
                            @if(isset($flightData['travelerPricings']))
                                @foreach($flightData['travelerPricings'] as $pricing)
                                    <div class="flex justify-between text-xs text-slate-600 mb-2">
                                        <span>{{ ucfirst($pricing['travelerType']) }} x (1)</span>
                                        <span class="font-medium">₦{{ number_format($pricing['price']['total']) }}</span>
                                    </div>
                                @endforeach
                            @endif
                            @if(isset($flightData['verifiedPriceBreakdown']))
                                <div class="flex justify-between text-xs text-slate-600 mb-2">
                                    <span>Tax & fees</span>
                                    <span class="font-medium">₦{{ number_format($flightData['verifiedPriceBreakdown']['taxesAndFees']) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-xs text-slate-600 mb-2">
                                <span>Discount</span>
                                <span class="font-medium">₦0</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mt-6 pt-4 border-t border-slate-200">
                            <span class="text-sm font-bold text-slate-500">Total Price</span>
                            <span class="text-2xl font-bold text-brand-textDark">
                                @if(isset($flightData['verifiedPrice']['total']))
                                    ₦{{ number_format($flightData['verifiedPrice']['total']) }}
                                @else
                                    ₦0
                                @endif
                            </span>
                        </div>
                        
                        <div class="mt-2 text-[10px] text-sky-600 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> Prices are subject to change until booked
                        </div>
                    </div>

                    <!-- Help Box -->
                    <div class="bg-brand-lightBlue/30 p-5 border-t border-slate-100">
                        <h4 class="font-bold text-brand-blue text-sm mb-1">Need any help?</h4>
                        <p class="text-xs text-slate-500 mb-2">Our customer service teams are here to help you 24/7.</p>
                        <p class="text-xs text-slate-600 mb-1">Contact us on:</p>
                        <a href="tel:+2347057000247" class="text-sky-600 font-bold text-lg">+234 705 7000 247</a>
                    </div>
                </div>

            </aside>
        </div>
    </main>
@endsection
