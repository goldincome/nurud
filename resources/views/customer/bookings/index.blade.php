@extends('layouts.customer')

@section('title', 'Booking History')

@section('customer_content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Booking History</h1>
                <p class="text-slate-500">A detailed list of all your flight reservations.</p>
            </div>
            <a href="{{ url('/') }}"
                class="bg-brand-blue text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-brand-blue/20 hover:bg-sky-700 transition-all flex items-center">
                <i class="fas fa-plus mr-2 text-xs"></i> New Booking
            </a>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Booking Details
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Itinerary</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Total Price</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="block font-bold text-slate-800">{{ $booking->reference_number }}</span>
                                    <span
                                        class="text-[10px] text-slate-400 font-mono tracking-wider">{{ $booking->reservation_id ?? strtoupper(substr($booking->id, 0, 8)) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2 text-sm font-semibold text-slate-700">
                                        <span>{{ $booking->origin_location }}</span>
                                        <i class="fas fa-long-arrow-alt-right text-brand-blue/40"></i>
                                        <span>{{ $booking->origin_destination }}</span>
                                    </div>
                                    <div class="mt-1 flex items-center space-x-2">
                                        <span class="text-xs text-slate-500 flex items-center">
                                            <i class="fas fa-plane-departure mr-1 text-[10px] text-slate-400"></i>
                                            {{ $booking->departure_date ? $booking->departure_date->format('d M, Y') : 'N/A' }}
                                        </span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-xs text-slate-500">{{ $booking->cabin }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                    Created {{ $booking->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900">{{ number_format($booking->total_price, 2) }}
                                            {{ $booking->currency }}</span>
                                        <span
                                            class="text-[10px] text-slate-400 capitalize">{{ str_replace('_', ' ', $booking->payment_method->value ?? $booking->payment_method) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'confirmed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'pending_payment' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            'reserved' => 'bg-brand-blue/10 text-brand-blue border-brand-blue/20',
                                        ][$booking->status->value] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $booking->status->value) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('customer.bookings.show', $booking->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:border-brand-blue hover:text-brand-blue hover:bg-brand-blue/5 transition-all"
                                            title="View Details">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @if(in_array($booking->status->value, ['confirmed', 'pending_payment']))
                                            <a href="{{ route('customer.bookings.ticket', $booking->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-600 hover:border-brand-orange hover:text-brand-orange hover:bg-brand-orange/5 transition-all"
                                                title="{{ $booking->status->value === 'confirmed' ? 'Download Ticket' : 'Download Reservation' }}">
                                                <i class="fas fa-file-download text-sm"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-slate-500">
                                    <i class="fas fa-folder-open text-4xl mb-4 block opacity-20"></i>
                                    No booking history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection