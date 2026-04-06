@extends('layouts.customer')

@section('title', 'Dashboard')

@section('customer_content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Welcome back, {{ auth()->user()->first_name }}! 👋</h1>
            <p class="text-slate-500">Manage your bookings and payments from your personal dashboard.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Bookings -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-plane text-8xl text-brand-blue"></i>
                </div>
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-brand-blue/10 text-brand-blue flex items-center justify-center text-xl">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Bookings</p>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $stats['total_bookings'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Pending Payment -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-clock text-8xl text-amber-500"></i>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pending Payment</p>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $stats['pending_payment'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Completed Bookings -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fas fa-check-circle text-8xl text-emerald-500"></i>
                </div>
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Completed Booking</p>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $stats['completed_booking'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="font-bold text-slate-800">5 most Recent Bookings</h2>
                <a href="{{ route('customer.bookings.index') }}"
                    class="text-sm font-semibold text-brand-blue hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Booking ID</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Route</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-slate-800">#{{ strtoupper(substr($booking->id, 0, 8)) }}</span>
                                    <p class="text-[10px] text-slate-400 mt-1 uppercase">{{ $booking->reservation_id }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-semibold text-slate-700">{{ $booking->origin_location }}</span>
                                        <i class="fas fa-arrow-right text-[10px] text-slate-400"></i>
                                        <span class="font-semibold text-slate-700">{{ $booking->origin_destination }}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1 uppercase">{{ $booking->carrier_code }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $booking->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-slate-900">{{ number_format($booking->total_price, 2) }}
                                        {{ $booking->currency }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'confirmed' => 'bg-emerald-100 text-emerald-700',
                                            'pending_payment' => 'bg-amber-100 text-amber-700',
                                            'cancelled' => 'bg-rose-100 text-rose-700',
                                            'reserved' => 'bg-brand-blue/10 text-brand-blue',
                                        ][$booking->status->value] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                                        {{ str_replace('_', ' ', strtoupper($booking->status->value)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('customer.bookings.show', $booking->id) }}"
                                            class="p-1.5 text-brand-blue hover:bg-brand-blue/10 rounded-lg transition-colors"
                                            title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($booking->status->value === 'confirmed')
                                            <a href="{{ route('customer.bookings.ticket', $booking->id) }}"
                                                class="p-1.5 text-brand-orange hover:bg-brand-orange/10 rounded-lg transition-colors"
                                                title="Download Ticket">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                                            <i class="fas fa-ticket-alt text-2xl"></i>
                                        </div>
                                        <h3 class="font-bold text-slate-800">No bookings yet</h3>
                                        <p class="text-sm text-slate-500 mt-1">Ready for your next adventure? Start your first
                                            booking today!</p>
                                        <a href="{{ url('/') }}"
                                            class="mt-4 bg-brand-blue text-white px-6 py-2 rounded-full text-sm font-semibold shadow-lg shadow-brand-blue/20">Find
                                            Flights</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection