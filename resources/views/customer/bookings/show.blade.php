@extends('layouts.customer')

@section('title', 'Booking Details - #' . strtoupper(substr($booking->id, 0, 8)))

@section('customer_content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('customer.bookings.index') }}"
                        class="text-slate-400 hover:text-brand-blue transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-slate-900">Booking Details</h1>
                </div>
                <p class="text-slate-500">View information about your flight to {{ $booking->origin_destination }}.</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($booking->status->value === 'confirmed')
                    <a href="{{ route('customer.bookings.ticket', $booking->id) }}" target="_blank"
                        class="bg-brand-orange text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-brand-orange/20 hover:bg-orange-600 transition-all flex items-center">
                        <i class="fas fa-file-download mr-2"></i> Download Ticket
                    </a>
                @elseif($booking->status->value === 'pending_payment')
                    <a href="{{ route('customer.bookings.ticket', $booking->id) }}" target="_blank"
                        class="bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 hover:bg-slate-200 transition-all flex items-center">
                        <i class="fas fa-file-pdf mr-2 text-red-500"></i> Download Reservation
                    </a>
                    <a href="#"
                        class="bg-brand-blue text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-brand-blue/20 hover:bg-sky-700 transition-all flex items-center">
                        <i class="fas fa-credit-card mr-2"></i> Complete Payment
                    </a>
                @endif
            </div>
        </div>

        <!-- Booking Info Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Flight Itinerary -->
                @foreach($booking->itineraries->sortBy('itinerary_index') as $itinerary)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h2 class="font-bold text-slate-800 flex items-center">
                                    <i
                                        class="fas fa-plane{{ $itinerary->itinerary_index === 2 ? '-arrival' : '-departure' }} mr-3 text-brand-blue"></i>
                                    {{ $itinerary->itinerary_title ?? 'Flight ' . $itinerary->itinerary_index }}
                                </h2>
                                <p class="text-xs text-slate-500 mt-1">{{ $itinerary->itinerary_summary }}</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center gap-1.5 bg-blue-50 text-brand-blue px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                    <i class="fas fa-clock"></i> {{ $itinerary->duration }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            @foreach($itinerary->segments ?? [] as $segIndex => $segment)
                                {{-- Layover indicator --}}
                                @if($segIndex > 0 && !empty($segment['layOverDuration']))
                                    <div
                                        class="flex items-center gap-3 py-3 my-4 mx-2 px-4 bg-amber-50 border border-amber-100 rounded-xl">
                                        <i class="fas fa-hourglass-half text-amber-500 text-sm"></i>
                                        <p class="text-[11px] font-bold text-amber-800 uppercase tracking-tight">
                                            Layover: {{ $segment['layOverDuration'] }} in
                                            {{ $segment['segmentDeparture']['airport']['city'] ?? '' }}
                                        </p>
                                    </div>
                                @endif

                                <div class="flex items-start space-x-6 relative">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-white border-2 border-brand-blue/20 flex items-center justify-center text-brand-blue z-10 shadow-sm shadow-brand-blue/10">
                                            <i class="fas fa-plane-departure text-xs"></i>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 h-full bg-slate-100 absolute top-10 left-5"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-8">
                                        <div class="grid grid-cols-2 gap-8 mb-6">
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                                    Departure</p>
                                                <h4 class="text-xl font-bold text-slate-900">
                                                    {{ $segment['segmentDeparture']['airport']['iataCode'] ?? '' }}</h4>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('h:i A') : '--:--' }}
                                                </p>
                                                <p class="text-xs text-slate-500">
                                                    {{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('D, M d Y') : '' }}
                                                </p>
                                                <p class="text-[10px] text-slate-400 mt-1 uppercase">
                                                    {{ $segment['segmentDeparture']['airport']['name'] ?? '' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                                    Arrival</p>
                                                <h4 class="text-xl font-bold text-slate-900">
                                                    {{ $segment['segmentArrival']['airport']['iataCode'] ?? '' }}</h4>
                                                <p class="text-sm font-semibold text-slate-700">
                                                    {{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('h:i A') : '--:--' }}
                                                </p>
                                                <p class="text-xs text-slate-500">
                                                    {{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('D, M d Y') : '' }}
                                                </p>
                                                <p class="text-[10px] text-slate-400 mt-1 uppercase">
                                                    {{ $segment['segmentArrival']['airport']['name'] ?? '' }}</p>
                                            </div>
                                        </div>

                                        <div
                                            class="bg-slate-50 p-4 rounded-xl flex items-center justify-between border border-slate-100">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center border border-slate-200">
                                                    <i class="fas fa-plane text-brand-blue"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800">
                                                        {{ $segment['carrier']['name'] ?? $segment['carrier_code'] ?? 'N/A' }}
                                                        {{ $segment['number'] ?? '' }}</p>
                                                    <p class="text-[10px] text-slate-500 uppercase font-medium tracking-tighter">
                                                        {{ $booking->cabin }} • Class {{ $segment['class'] ?? 'N/A' }} • Aircraft
                                                        {{ $segment['aircraft']['code'] ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase">Duration</p>
                                                <p class="text-xs font-bold text-slate-700">{{ $segment['duration'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Travelers -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center space-x-3 bg-slate-50/50">
                        <i class="fas fa-users text-brand-blue"></i>
                        <h2 class="font-bold text-slate-800">Traveler Information</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Name</th>
                                    <th class="px-6 py-4">Type</th>
                                    <th class="px-6 py-4">Gender</th>
                                    <th class="px-6 py-4">Document</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($booking->travelers as $traveler)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800">{{ $traveler->first_name }}
                                                {{ $traveler->last_name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono italic">
                                                {{ $traveler->date_of_birth }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-0.5 bg-brand-blue/5 text-brand-blue rounded text-[10px] font-bold uppercase">{{ $traveler->traveler_type }}</span>
                                        </td>
                                        <td class="px-6 py-4 capitalize text-sm text-slate-600">
                                            {{ $traveler->gender }}
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                            {{ $traveler->passport_number }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Stats -->
            <div class="space-y-8">
                <!-- Reservation Info -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Reservation Info</h3>

                    <div class="flex items-center space-x-4 mb-8">
                        @php
                            $statusConfig = [
                                'confirmed' => ['color' => 'bg-emerald-500', 'icon' => 'check', 'label' => 'Confirmed'],
                                'pending_payment' => ['color' => 'bg-amber-500', 'icon' => 'hourglass-half', 'label' => 'Pending Payment'],
                                'cancelled' => ['color' => 'bg-rose-500', 'icon' => 'times', 'label' => 'Cancelled'],
                                'reserved' => ['color' => 'bg-brand-blue', 'icon' => 'clock', 'label' => 'Reserved'],
                            ][$booking->status->value] ?? ['color' => 'bg-slate-500', 'icon' => 'info', 'label' => strtoupper($booking->status->value)];
                        @endphp
                        <div
                            class="w-12 h-12 rounded-2xl {{ $statusConfig['color'] }} text-white flex items-center justify-center text-xl shadow-lg shadow-{{ str_replace('bg-', '', $statusConfig['color']) }}/20">
                            <i class="fas fa-{{ $statusConfig['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Current Status</p>
                            <p class="font-bold text-slate-900 leading-tight uppercase tracking-tight">
                                {{ $statusConfig['label'] }}</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase mb-1">Reference No. / PNR</span>
                            <div class="flex flex-col space-y-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-slate-800 font-mono text-lg tracking-wider">{{ $booking->reference_number }}</span>
                                    <button class="text-slate-300 hover:text-brand-blue transition-colors"><i class="fas fa-copy text-xs"></i></button>
                                </div>
                                <span class="text-xs text-slate-400 font-mono tracking-wider">{{ $booking->reservation_id }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Booking Date</span>
                                <span
                                    class="text-sm font-bold text-slate-800">{{ $booking->created_at->format('d M, Y') }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Carrier</span>
                                <span class="text-sm font-bold text-slate-800">{{ $booking->carrier_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price & Payment -->
                <div
                    class="bg-brand-blue p-8 rounded-2xl shadow-xl shadow-brand-blue/20 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 text-white/5 rotate-12 transform scale-150">
                        <i class="fas fa-credit-card text-9xl"></i>
                    </div>

                    <h3 class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-8">Payment Summary</h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-sm">
                            <span class="text-white/60">Base Fare</span>
                            <span class="font-semibold">{{ number_format($booking->base_price, 2) }}
                                {{ $booking->currency }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-white/60">Taxes & Fees</span>
                            <span
                                class="font-semibold">{{ number_format($booking->taxes_and_fees + $booking->markup_fee, 2) }}
                                {{ $booking->currency }}</span>
                        </div>
                        <div class="pt-6 border-t border-white/10 flex justify-between items-baseline">
                            <span class="font-bold text-brand-orange text-xs uppercase tracking-widest">Total Paid</span>
                            <span class="text-3xl font-black text-white">{{ number_format($booking->total_price, 2) }} <span
                                    class="text-xs font-normal">{{ $booking->currency }}</span></span>
                        </div>
                    </div>

                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-brand-orange text-white flex items-center justify-center shadow-lg shadow-brand-orange/20">
                                <i class="fas fa-receipt text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-white/60 uppercase font-bold tracking-tight">Payment Method</p>
                                <p class="text-sm font-bold capitalize">
                                    {{ str_replace('_', ' ', $booking->payment_method->value ?? $booking->payment_method) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Contact Details</h3>
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Customer</p>
                                <p class="text-sm font-bold text-slate-800">{{ $booking->customer_first_name }}
                                    {{ $booking->customer_last_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Email</p>
                                <p class="text-sm font-bold text-slate-800 truncate max-w-[150px]">
                                    {{ $booking->customer_email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 uppercase tracking-tighter">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Phone</p>
                                <p class="text-sm font-bold text-slate-800">{{ $booking->contact_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection