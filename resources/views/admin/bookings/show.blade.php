@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('admin.bookings.index') }}" class="hover:text-brand-blue transition">Bookings</a>
                <span>/</span>
                <span class="text-slate-800 font-medium">Ref: {{ $booking->reference_number }}</span>
            </div>
            
            <div class="flex gap-2">
                @if($booking->status->value === 'pending_payment')
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm this booking?');">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition shadow-sm">
                            <i class="fas fa-check mr-2"></i> Confirm Booking
                        </button>
                    </form>
                @endif
                
                @if($booking->status->value !== 'cancelled')
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" onsubmit="return confirm('Cancel this booking? This action cannot be undone.');">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="bg-white border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition shadow-sm">
                            <i class="fas fa-times mr-2"></i> Cancel Booking
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('bookings.ticket.download', $booking->id) }}" target="_blank" class="bg-brand-blue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm">
                    <i class="fas fa-download mr-2"></i> Download Ticket
                </a>
            </div>
        </div>

        <!-- Main Info -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Booking Information</h3>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                    @if($booking->status->value === 'confirmed') bg-green-100 text-green-600
                    @elseif($booking->status->value === 'cancelled') bg-red-100 text-red-600
                    @elseif($booking->status->value === 'pending_payment') bg-yellow-100 text-yellow-600
                    @else bg-slate-100 text-slate-600 @endif">
                    {{ str_replace('_', ' ', $booking->status->value) }}
                </span>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Reference No. / PNR</p>
                    <p class="text-slate-800 font-mono font-bold">{{ $booking->reference_number }}</p>
                    <p class="text-xs text-slate-500 font-mono mt-1">{{ $booking->reservation_id }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Date Created</p>
                    <p class="text-slate-800">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Created By</p>
                    <p class="text-slate-800">{{ $booking->user ? $booking->user->name : 'Guest / System' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Total Price</p>
                    <p class="text-slate-800 font-bold text-lg">{{ $booking->currency }} {{ number_format($booking->total_price + $booking->markup_fee) }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Payment Method</p>
                    <p class="text-slate-800 capitalize">{{ str_replace('_', ' ', $booking->payment_method->value ?? 'Not Set') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Route</p>
                    <p class="text-slate-800">{{ $booking->origin_location }} <i class="fas fa-arrow-right text-xs mx-1 text-slate-400"></i> {{ $booking->origin_destination }}</p>
                </div>
            </div>
        </div>

        <!-- Price & Baggage Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Baggage -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800">Baggage Allowance</h3>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $bagInfo = $booking->travelerPricings[0]->fare_details_by_segment[0]['includedCheckedBags'] ?? null;
                        $displayBag = 'Included';
                        if ($bagInfo) {
                            if (isset($bagInfo['quantity'])) {
                                $displayBag = $bagInfo['quantity'] . ' Bag(s) x 23kg';
                            } else if (isset($bagInfo['weight'])) {
                                $displayBag = '1 Bag x ' . $bagInfo['weight'] . ($bagInfo['weightUnit'] ?? 'kg');
                            }
                        }
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-suitcase-rolling text-brand-blue text-lg w-6"></i>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase">Checked Baggage</p>
                                <p class="text-slate-800 font-medium">{{ $displayBag }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-briefcase text-brand-orange text-lg w-6"></i>
                            <div>
                                <p class="text-xs font-bold text-slate-500 uppercase">Cabin Baggage</p>
                                <p class="text-slate-800 font-medium">7kg Handbag</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800">Price Breakdown</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Flight Base Fare</p>
                        @php
                            $groupedDetails = $booking->travelerPricings->groupBy('traveler_type');
                        @endphp
                        @foreach($groupedDetails as $type => $group)
                            @php
                                $count = $group->count();
                                $totalGroupPrice = $group->sum(function ($pricing) {
                                    return $pricing->price['base'] ?? 0;
                                });
                                $label = \App\Enums\TravelerType::from($type)->label();
                            @endphp
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600">{{ $label }} x ({{ $count }})</span>
                                <span class="font-medium text-slate-800">{{ $booking->currency }} {{ number_format($totalGroupPrice, 2) }}</span>
                            </div>
                        @endforeach

                        <div class="pt-3 border-t border-slate-100 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600">Taxes and Fees</span>
                                <span class="font-medium text-slate-800">{{ $booking->currency }} {{ number_format($booking->taxes_and_fees + $booking->markup_fee) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm text-slate-400">
                                <span>Discount</span>
                                <span>{{ $booking->currency }} 0</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                            <span class="font-bold text-slate-800">Total Price</span>
                            <span class="text-xl font-bold text-brand-blue">{{ $booking->currency }} {{ number_format($booking->total_price + $booking->markup_fee) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flight Details (from Itineraries) -->
        @forelse($booking->itineraries->sortBy('itinerary_index') as $itinerary)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-800">
                        <i class="fas fa-plane{{ $itinerary->itinerary_index === 2 ? '-arrival' : '-departure' }} text-brand-blue mr-2"></i>
                        {{ $itinerary->itinerary_title ?? 'Flight ' . $itinerary->itinerary_index }}
                    </h3>
                    <p class="text-xs text-slate-800 mt-0.5">{{ $itinerary->itinerary_summary }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-brand-blue px-3 py-1 rounded-full text-xs font-bold">
                        <i class="fas fa-clock"></i> {{ $itinerary->duration }}
                    </span>
                    @if(count($itinerary->segments ?? []) > 1)
                        <p class="text-[12px] text-slate-800 mt-1">{{ count($itinerary->segments) - 1 }} stop(s)</p>
                    @else
                        <p class="text-[12px] text-green-800 font-medium mt-1">Direct</p>
                    @endif
                </div>
            </div>

            <div class="p-6 space-y-0">
                @foreach($itinerary->segments ?? [] as $segIndex => $segment)
                    {{-- Layover indicator between segments --}}
                    @if($segIndex > 0 && !empty($segment['layOverDuration']))
                        <div class="flex items-center gap-3 py-3 my-2 mx-4 px-4 bg-amber-50 border border-amber-100 rounded-lg">
                            <i class="fas fa-hourglass-half text-amber-500"></i>
                            <div>
                                <p class="text-xs font-bold text-amber-800">Layover in {{ $segment['segmentDeparture']['airport']['city'] ?? '' }}</p>
                                <p class="text-[12px] text-amber-600">{{ $segment['layOverDuration'] }} • {{ $segment['segmentDeparture']['airport']['name'] ?? '' }} ({{ $segment['segmentDeparture']['airport']['iataCode'] ?? '' }})</p>
                            </div>
                        </div>
                    @endif

                    {{-- Segment card --}}
                    <div class="flex items-stretch gap-4 py-4 {{ $segIndex > 0 ? 'border-t border-slate-100' : '' }}">
                        {{-- Departure --}}
                        <div class="flex-1">
                            <p class="text-lg font-bold text-slate-800">
                                {{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('H:i') : '--:--' }}
                            </p>
                            <p class="text-xs text-slate-800">
                                {{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('D, M d, Y') : '' }}
                            </p>
                            <p class="text-sm font-semibold text-brand-blue mt-1">
                                {{ $segment['segmentDeparture']['airport']['iataCode'] ?? '' }}
                            </p>
                            <p class="text-xs text-slate-800">
                                {{ $segment['segmentDeparture']['airport']['name'] ?? '' }}
                            </p>
                            <p class="text-[10px] text-slate-800">
                                {{ $segment['segmentDeparture']['airport']['city'] ?? '' }}, {{ $segment['segmentDeparture']['airport']['country'] ?? '' }}
                            </p>
                        </div>

                        {{-- Flight line --}}
                        <div class="flex flex-col items-center justify-center min-w-[140px]">
                            <p class="text-[10px] font-bold text-slate-800 uppercase mb-1">{{ $segment['duration'] ?? '' }}</p>
                            <div class="flex items-center w-full">
                                <div class="w-2 h-2 rounded-full bg-brand-blue"></div>
                                <div class="flex-1 h-px bg-slate-300 mx-1 relative">
                                    <i class="fas fa-plane text-brand-blue text-[12px] absolute -top-[5px] left-1/2 -translate-x-1/2"></i>
                                </div>
                                <div class="w-2 h-2 rounded-full bg-brand-blue"></div>
                            </div>
                            <div class="mt-1.5 text-center">
                                <p class="text-[12px] text-slate-800">
                                    {{ $segment['carrier']['name'] ?? '' }}
                                </p>
                                <p class="text-[12px] font-mono text-slate-800">
                                    {{ $segment['carrier']['iataCode'] ?? '' }}{{ $segment['number'] ?? '' }}
                                    • Aircraft {{ $segment['aircraft']['code'] ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        {{-- Arrival --}}
                        <div class="flex-1 text-right">
                            <p class="text-lg font-bold text-slate-800">
                                {{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('H:i') : '--:--' }}
                            </p>
                            <p class="text-xs text-slate-800">
                                {{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('D, M d, Y') : '' }}
                            </p>
                            <p class="text-sm font-semibold text-brand-blue mt-1">
                                {{ $segment['segmentArrival']['airport']['iataCode'] ?? '' }}
                            </p>
                            <p class="text-xs text-slate-800">
                                {{ $segment['segmentArrival']['airport']['name'] ?? '' }}
                            </p>
                            <p class="text-[10px] text-slate-800">
                                {{ $segment['segmentArrival']['airport']['city'] ?? '' }}, {{ $segment['segmentArrival']['airport']['country'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @empty
        {{-- Fallback if no itineraries exist --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Flight Details</h3>
            </div>
            <div class="p-6">
               <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-2xl font-bold text-brand-blue">{{ $booking->origin_location }}</p>
                        <p class="text-xs text-slate-500">Departure</p>
                    </div>
                    <div class="flex-1 px-8 text-center">
                        <div class="w-full h-px bg-slate-200 relative top-3"></div>
                        <i class="fas fa-plane text-slate-400 bg-white px-2 relative z-10"></i>
                        <p class="text-xs text-slate-500 mt-2">{{ $booking->carrier_code }} • {{ $booking->class ?? 'Economy' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-brand-blue">{{ $booking->origin_destination }}</p>
                        <p class="text-xs text-slate-500">Arrival</p>
                    </div>
               </div>
               <div class="grid grid-cols-2 gap-4 text-sm border-t border-slate-100 pt-4">
                    <div>
                        <span class="text-slate-500">Departure Date:</span>
                        <span class="font-medium text-slate-800 ml-2">{{ $booking->departure_date ? $booking->departure_date->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
               </div>
            </div>
        </div>
        @endforelse

        <!-- Travelers -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
             <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Travelers</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">DOB</th>
                            <th class="px-6 py-3">Gender</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($booking->travelers as $index => $traveler)
                            <tr>
                                <td class="px-6 py-3 font-medium text-slate-800">
                                    {{ $traveler->first_name }} {{ $traveler->last_name }}
                                </td>
                                <td class="px-6 py-3 text-slate-600 capitalize">{{ \App\Enums\TravelerType::from($booking->travelerPricings[$index]->traveler_type)->label()}}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $traveler->date_of_birth ? \Carbon\Carbon::parse($traveler->date_of_birth)->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-6 py-3 text-slate-600 capitalize">{{ $traveler->gender->label() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-slate-500">No traveler information found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer Contact -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Contact Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                     <p class="text-xs font-bold text-slate-500 uppercase mb-1">Customer Name</p>
                     <p class="text-slate-800">{{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</p>
                </div>
                <div>
                     <p class="text-xs font-bold text-slate-500 uppercase mb-1">Email Address</p>
                     <p class="text-slate-800">{{ $booking->customer_email }}</p>
                </div>
                <div>
                     <p class="text-xs font-bold text-slate-500 uppercase mb-1">Phone Number</p>
                     <p class="text-slate-800">{{ $booking->contact_phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        
    </div>
@endsection
