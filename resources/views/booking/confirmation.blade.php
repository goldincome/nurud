@extends('layouts.front')

@section('content')
    <div class="bg-brand-blue py-8">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="relative flex items-center justify-between w-full">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-brand-orange z-0"></div>

                @php $steps = ['Search flight', 'Traveler Details', 'Make payment', 'Confirmation']; @endphp
                @foreach($steps as $index => $step)
                    <div class="relative z-10 flex flex-col items-center">
                        <div
                            class="w-8 h-8 rounded-full bg-brand-orange flex items-center justify-center text-white font-bold text-sm mb-2 shadow-md">
                            @if($index < 3) <i class="fas fa-check"></i> @else 4 @endif
                        </div>
                        <span
                            class="text-xs {{ $index == 3 ? 'text-white' : 'text-brand-orange' }} font-medium">{{ $step }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 mb-1">Trip booked on hold</h1>
                    <p class="text-sm text-slate-500">Your trip has been booked on hold.</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex flex-col"><span class="font-bold text-slate-700">Reservation ID</span><span
                                class="text-slate-600">{{ $booking->reservation_id ?? 'VN260213-1359-44104' }}</span></div>
                        <div class="flex flex-col"><span class="font-bold text-slate-700">Booking sent to</span><span
                                class="text-slate-600">{{ $booking->email ?? 'ifylovely@mailinator.com' }}</span></div>
                        <div class="flex flex-col"><span class="font-bold text-slate-700">Booking date</span><span
                                class="text-slate-600">{{ now()->format('M d, Y') }}</span></div>
                        <div class="flex flex-col"><span class="font-bold text-slate-700">Payment status</span><span
                                class="text-orange-600 font-bold uppercase">Pending</span></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-brand-blue font-bold mb-4 border-b border-slate-100 pb-2">Travellers Details</h3>
                    <div class="space-y-4">
                        @if(isset($flightData['travelerPricings']))
                            @foreach($flightData['travelerPricings'] as $index => $traveler)
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">{{$booking->travelers[$index]->first_name}}
                                            {{$booking->travelers[$index]->last_name}}</span>
                                        {{-- <span class="text-xs text-slate-400">Ref: {{ $traveler['travelerId'] }}</span> --}}
                                    </div>
                                    <span
                                        class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold">{{ $traveler['travelerType'] }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    @foreach($flightData['itineraries'] as $itinerary)
                        <h4 class="text-brand-orange font-bold text-sm mb-6">
                            Flight leaves from {{ $itinerary['segments'][0]['segmentDeparture']['airport']['city'] }} -
                            {{ \Carbon\Carbon::parse($itinerary['segments'][0]['segmentDeparture']['at'])->format('H:i D, M d Y') }}
                        </h4>

                        @foreach($itinerary['segments'] as $index => $segment)
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-10 h-10 flex items-center justify-center">
                                    @if(isset($segment['carrier']['iataCode']))
                                        <img src="https://pics.avs.io/200/60/{{ $segment['carrier']['iataCode'] }}.png"
                                            class="h-8 object-contain" alt="{{ $segment['carrier']['name'] ?? 'Airline' }}">
                                    @else
                                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-plane text-brand-blue"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 flex items-center justify-between">
                                    <div class="text-center">
                                        <span
                                            class="block font-bold">{{ \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('H:i') }}</span>
                                        <span
                                            class="text-xs text-slate-800">{{ $segment['segmentDeparture']['airport']['city'] }}({{ $segment['segmentDeparture']['airport']['iataCode'] }})</span>
                                    </div>
                                    <div class="flex-1 px-8 relative">
                                        <div class="h-[1px] bg-slate-300 w-full relative">
                                            <div
                                                class="absolute -top-3 left-1/2 -translate-x-1/2 text-[10px] text-slate-800 bg-white px-2">
                                                {{ str_replace(['PT', 'H', 'M'], ['', 'h ', 'm'], $segment['duration']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <span
                                            class="block font-bold">{{ \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('H:i') }}</span>
                                        <span
                                            class="text-xs text-slate-800">{{ $segment['segmentArrival']['airport']['city'] }}({{ $segment['segmentArrival']['airport']['iataCode'] }})</span>
                                    </div>
                                </div>
                            </div>

                            @if(!$loop->last)
                                <div
                                    class="bg-slate-50 text-center py-2 text-xs text-slate-500 rounded-lg my-4 border border-slate-100 italic">
                                    Layover in {{ $segment['segmentArrival']['airport']['city'] }}
                                    ({{ $segment['segmentArrival']['airport']['iataCode'] }})
                                </div>
                            @endif
                        @endforeach
                        @if(!$loop->last)
                        <hr class="my-8 border-dashed"> @endif
                    @endforeach

                    <div class="flex justify-center mt-12">
                        <a href="{{ url('/') }}"
                            class="bg-brand-orange hover:bg-brand-orangeHover text-white font-bold py-4 px-16 rounded-full shadow-lg transition-all transform hover:scale-105">
                            Finish
                        </a>
                    </div>
                </div>
            </div>

            <aside class="lg:col-span-1">
                <!-- Booking Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                    <!-- Image Header -->
                    <div class="h-32 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=2070&auto=format&fit=crop"
                            class="w-full h-full object-cover" alt="Destination">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-lg text-brand-textDark mb-1">Booking Summary</h3>
                        <div class="w-10 h-1 bg-brand-orange mb-4"></div>

                        @if(isset($flightData['itineraries']) && count($flightData['itineraries']) > 0)
                            @foreach($flightData['itineraries'] as $itineraryIndex => $itinerary)
                                @if($itineraryIndex > 0)
                                <div class="border-t border-dashed border-slate-200 my-4"></div> @endif

                                <div class="mb-6">
                                    @if(
                                                    isset($itinerary['segments'][0]['segmentDeparture']['airport']['city']) &&
                                                    isset($itinerary['segments'][count($itinerary['segments']) -
                                                        1]['segmentArrival']['airport']['city'])
                                                )
                                                <h4 class="font-bold text-md text-brand-textDark">
                                                    {{ $itinerary['segments'][0]['segmentDeparture']['airport']['city'] }} -
                                                    {{ $itinerary['segments'][count($itinerary['segments']) -
                                        1]['segmentArrival']['airport']['city'] }}
                                                </h4>
                                    @endif
                                    @if(
                                            isset($itinerary['segments'][0]['segmentDeparture']['at']) &&
                                            isset($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at'])
                                        )
                                        @php
                                            $startDate =
                                                \Carbon\Carbon::parse($itinerary['segments'][0]['segmentDeparture']['at'])->format('M d');
                                            $endDate = \Carbon\Carbon::parse($itinerary['segments'][count($itinerary['segments']) -
                                                1]['segmentArrival']['at'])->format('M d');
                                        @endphp
                                        <p class="text-xs text-slate-600">{{ $startDate }} - {{ $endDate }}</p>
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
                                                <div class="text-xs text-slate-600">{{ $depTimeFormatted }}</div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            @if(
                                                    isset($itinerary['segments'][count($itinerary['segments']) -
                                                        1]['segmentArrival']['at'])
                                                )
                                                @php
                                                    $arrTime = \Carbon\Carbon::parse($itinerary['segments'][count($itinerary['segments']) -
                                                        1]['segmentArrival']['at']);
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
                                @php
                                    // 1. Group the travelers by type (ADULT, CHILD, HELD_INFANT)
                                    $groupedDetails = collect($flightData['travelerPricings'])->groupBy('travelerType');
                                @endphp

                                @foreach($groupedDetails as $type => $group)
                                    @php
                                        // 2. Calculate the count for this specific group
                                        $count = $group->count();

                                        // 3. Sum the price for all travelers in this group
                                        $totalGroupPrice = $group->sum(function ($traveler) {
                                            return $traveler['price']['base'];
                                        });

                                        // 4. Format the Label
                                        $label = match ($type) {
                                            'HELD_INFANT' => 'Infant',
                                            'CHILD' => 'Child',
                                            default => 'Adult'
                                        };
                                    @endphp

                                    <div class="flex justify-between text-xs text-slate-600 mb-2">
                                        {{-- Output: "Adult x (2)" --}}
                                        <span>{{ $label }} x ({{ $count }})</span>

                                        {{-- Output: Total price for that group --}}
                                        <span class="font-medium">₦{{ number_format($totalGroupPrice, 2) }}</span>
                                    </div>
                                @endforeach
                            @endif
                            @if(isset($flightData['verifiedPriceBreakdown']['taxesAndFees']))
                                                <div class="flex justify-between text-xs text-slate-600 mb-2">
                                                    <span>Taxes and Fees</span>
                                                    <span class="font-medium">₦{{
                                number_format($flightData['verifiedPriceBreakdown']['taxesAndFees'], 2) }}</span>
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

                        <div class="mt-2 text-[14px] text-sky-600 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> Prices are subject to change until booked
                        </div>
                    </div>

                    <!-- Help Box -->
                    <div class="bg-brand-lightBlue/30 p-5 border-t border-slate-100">
                        <h4 class="font-bold text-brand-blue text-sm mb-1">Need any help?</h4>
                        <p class="text-xs text-slate-500 mb-2">Our customer service teams are here to help you 24/7.</p>
                        <p class="text-xs text-slate-600 mb-1">Contact us on:</p>
                        <a href="tel:+2348096964423" class="text-sky-600 font-bold text-lg">+234 809 696 4423</a>
                    </div>
                </div>
            </aside>
        </div>
    </main>
@endsection