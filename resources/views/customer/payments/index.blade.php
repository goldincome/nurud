@extends('layouts.customer')

@section('title', 'Payments')

@section('customer_content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Payment History</h1>
            <p class="text-slate-500">Track all your transactions and payment statuses.</p>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Transaction ID
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Booking Ref</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="font-mono text-xs font-semibold text-slate-700">{{ $payment->transaction_id ?? 'N/A' }}</span>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Payment #{{ $payment->id }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('customer.bookings.show', $payment->booking_id) }}"
                                        class="text-brand-blue hover:underline font-bold text-sm">
                                        {{ $payment->booking->reservation_id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $payment->created_at->format('M d, Y') }}
                                    <span class="block text-[10px]">{{ $payment->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-bold text-slate-900">{{ number_format($payment->amount, 2) }}
                                        {{ $payment->currency }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="capitalize text-xs px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-medium">
                                        {{ str_replace('_', ' ', $payment->payment_method->value ?? $payment->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'completed' => 'text-emerald-600',
                                            'pending' => 'text-amber-600',
                                            'failed' => 'text-rose-600',
                                            'refunded' => 'text-slate-600',
                                        ][$payment->status->value ?? $payment->status] ?? 'text-slate-700';

                                        $statusDot = [
                                            'completed' => 'bg-emerald-600',
                                            'pending' => 'bg-amber-600',
                                            'failed' => 'bg-rose-600',
                                            'refunded' => 'bg-slate-600',
                                        ][$payment->status->value ?? $payment->status] ?? 'bg-slate-700';
                                    @endphp
                                    <div class="flex items-center {{ $statusClass }} font-bold text-xs uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }} mr-2 animate-pulse"></span>
                                        {{ $payment->status->value ?? $payment->status }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-slate-400 font-medium">
                                    <i class="fas fa-credit-card text-4xl mb-4 block opacity-10"></i>
                                    No payment transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection