@extends('layouts.admin')

@section('title', 'All Bookings')

@section('content')
    <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center bg-white rounded-t-xl">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Bookings</h2>
            <p class="text-sm text-slate-500">Manage all flight reservations.</p>
        </div>
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Ref No., PNR, Email, Name"
                class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/20">
            <button type="submit"
                class="bg-brand-blue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 border-t-0 rounded-b-xl overflow-hidden">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead
                    class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Ref ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Flight</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $booking->reference_number ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono tracking-wider">
                                    {{ $booking->reservation_id ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $booking->customer_first_name }}
                                    {{ $booking->customer_last_name }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $booking->customer_email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-800">{{ $booking->origin_location }} <i
                                        class="fas fa-arrow-right text-xs mx-1 text-slate-400"></i>
                                    {{ $booking->origin_destination }}</div>
                                <div class="text-xs text-slate-500">{{ $booking->carrier_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $booking->departure_date ? $booking->departure_date->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $booking->currency }} {{ number_format($booking->total_price + $booking->markup_fee) }}
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
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                    class="text-brand-blue hover:text-blue-700 font-medium text-xs">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-ticket-alt text-4xl text-slate-200 mb-2"></i>
                                    <p>No bookings found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
@endsection