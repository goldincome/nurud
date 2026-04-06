@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.customers.index') }}" class="hover:text-brand-blue transition">Customers</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">
                {{ $customer->first_name ?? '' }} {{ $customer->last_name ?? $customer->name }}
            </span>
        </div>

        {{-- Customer Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-[#002D72] to-[#003d99] px-8 py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? $customer->name)) }}&background=F58220&color=fff&size=80"
                        class="w-20 h-20 rounded-full border-4 border-white/20 shadow-lg" alt="">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">
                            {{ $customer->title ?? '' }}
                            {{ $customer->first_name ?? '' }}
                            {{ $customer->middle_name ?? '' }}
                            {{ $customer->last_name ?? $customer->name }}
                        </h2>
                        <p class="text-blue-200 mt-1">{{ $customer->email }}</p>
                        <div class="flex items-center gap-4 mt-3">
                            @if($customer->phone_no)
                                <span class="inline-flex items-center gap-1.5 text-sm text-blue-100">
                                    <i class="fas fa-phone text-xs"></i>
                                    {{ $customer->phone_code ?? '' }}{{ $customer->phone_no }}
                                </span>
                            @endif
                            @if($customer->city || $customer->country)
                                <span class="inline-flex items-center gap-1.5 text-sm text-blue-100">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                    {{ $customer->city ?? '' }}{{ $customer->city && $customer->country ? ', ' : '' }}{{ $customer->country ?? '' }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-1.5 text-sm text-blue-100">
                                <i class="fas fa-calendar text-xs"></i>
                                Joined {{ $customer->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-5 divide-x divide-slate-200 border-b border-slate-200">
                <div class="p-5 text-center">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['total_bookings'] }}</p>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Total Bookings</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-2xl font-bold text-green-600">₦{{ number_format($stats['total_spend']) }}</p>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Total Spend</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed_bookings'] }}</p>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Confirmed</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-2xl font-bold text-amber-500">{{ $stats['pending_bookings'] }}</p>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Pending</p>
                </div>
                <div class="p-5 text-center">
                    <p class="text-2xl font-bold text-red-500">{{ $stats['cancelled_bookings'] }}</p>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1">Cancelled</p>
                </div>
            </div>

            {{-- Additional Info --}}
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Gender</p>
                    <p class="text-slate-800 capitalize">{{ $customer->gender ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Address</p>
                    <p class="text-slate-800">{{ $customer->address ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Last Booking</p>
                    <p class="text-slate-800">
                        {{ $stats['last_booking_date'] ? \Carbon\Carbon::parse($stats['last_booking_date'])->format('M d, Y') : 'No bookings yet' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Bookings List --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">
                    <i class="fas fa-ticket-alt text-brand-blue mr-2"></i>
                    Booking History
                    <span class="text-sm font-normal text-slate-500 ml-2">({{ $stats['total_bookings'] }} total)</span>
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Ref ID</th>
                            <th class="px-6 py-4">Route</th>
                            <th class="px-6 py-4">Travel Date</th>
                            <th class="px-6 py-4">Travelers</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Payment</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Booked On</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono font-medium text-slate-800">{{ $booking->reservation_id ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-800 font-medium">
                                        {{ $booking->origin_location }}
                                        <i class="fas fa-arrow-right text-xs mx-1 text-slate-400"></i>
                                        {{ $booking->origin_destination }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $booking->carrier_code }} •
                                        {{ $booking->cabin ?? $booking->class ?? 'Economy' }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $booking->departure_date ? $booking->departure_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-slate-600">
                                        <i class="fas fa-user text-xs text-slate-400"></i>
                                        {{ $booking->travelers->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $booking->currency ?? '₦' }} {{ number_format($booking->total_price) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs capitalize text-slate-600">
                                        {{ str_replace('_', ' ', $booking->payment_method->value ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                                                @if($booking->status->value === 'confirmed') bg-green-100 text-green-600
                                                @elseif($booking->status->value === 'cancelled') bg-red-100 text-red-600
                                                @elseif($booking->status->value === 'pending_payment') bg-yellow-100 text-yellow-600
                                                @else bg-slate-100 text-slate-600 @endif">
                                        {{ str_replace('_', ' ', $booking->status->value) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $booking->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                        class="inline-flex items-center gap-1 text-brand-blue hover:text-blue-700 font-medium text-xs transition">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-plane-slash text-4xl text-slate-200 mb-3"></i>
                                        <p class="font-medium">No bookings found for this customer.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Back Button --}}
        <div>
            <a href="{{ route('admin.customers.index') }}"
                class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-brand-blue transition">
                <i class="fas fa-arrow-left"></i> Back to Customer List
            </a>
        </div>
    </div>
@endsection