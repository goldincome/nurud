@extends('layouts.admin')

@section('title', 'Financial Transactions')

@section('css')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .status-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header/Filter Section -->
    <div class="glass-card p-6 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Financial Transactions</h1>
                <p class="text-slate-500 text-sm">Monitor and manage all payment activities across the platform.</p>
            </div>
            
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-wrap gap-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Ref, PNR, Email..."
                        class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all w-64">
                </div>
                
                <select name="status" onchange="this.form.submit()" 
                    class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>

                <select name="method" onchange="this.form.submit()" 
                    class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    <option value="">All Methods</option>
                    @foreach($methods as $method)
                        <option value="{{ $method->value }}" {{ request('method') == $method->value ? 'selected' : '' }}>
                            {{ $method->label() }}
                        </option>
                    @endforeach
                </select>

                @if(request()->anyFilled(['search', 'status', 'method']))
                    <a href="{{ route('admin.transactions.index') }}" class="flex items-center text-slate-400 hover:text-rose-500 transition-colors px-2">
                        <i class="fas fa-times-circle text-lg"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Transaction Info</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Booking & Trip</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Method</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-[11px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-700 font-mono text-xs uppercase tracking-tight">#{{ substr($tx->transaction_ref, 0, 12) }}{{ strlen($tx->transaction_ref) > 12 ? '...' : '' }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">TxID: {{ $tx->id }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($tx->booking && $tx->booking->user)
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($tx->booking->user->name) }}&background=E2E8F0&color=64748B&size=32" 
                                        class="w-8 h-8 rounded-lg border border-slate-100" alt="Avatar">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-slate-700 leading-none mb-1">{{ $tx->booking->user->name }}</span>
                                        <span class="text-xs text-slate-400">{{ $tx->booking->user->email }}</span>
                                    </div>
                                </div>
                            @elseif($tx->booking)
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-700">{{ $tx->booking->customer_first_name }} {{ $tx->booking->customer_last_name }}</span>
                                    <span class="text-xs text-slate-400">{{ $tx->booking->customer_email }}</span>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 font-italic">No Customer Data</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($tx->booking)
                                <a href="{{ route('admin.bookings.show', $tx->booking->id) }}" class="group/link">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="text-sm font-bold text-brand-blue group-hover/link:underline">{{ $tx->booking->reference_number }}</span>
                                            @if(isset($tx->booking->route_mode))
                                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 border border-slate-200 uppercase font-black tracking-tighter">
                                                    {{ $tx->booking->route_mode->label() }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-medium">
                                            <span>PNR: {{ $tx->booking->reservation_id ?? 'N/A' }}</span>
                                            <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                            <span>{{ $tx->booking->origin_location }} <i class="fas fa-arrow-right text-[8px] mx-1"></i> {{ $tx->booking->origin_destination }}</span>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <span class="text-xs text-slate-300">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800">{{ $tx->currency }} {{ number_format($tx->amount, 2) }}</span>
                                <span class="text-[10px] text-slate-400 font-medium italic">Net Received</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($tx->payment_method->value === 'stripe')
                                    <span class="w-6 h-4 bg-[#635BFF] rounded-sm flex items-center justify-center">
                                        <i class="fab fa-stripe text-white text-[10px]"></i>
                                    </span>
                                @elseif($tx->payment_method->value === 'bank_transfer')
                                    <span class="w-6 h-4 bg-emerald-500 rounded-sm flex items-center justify-center">
                                        <i class="fas fa-university text-white text-[10px]"></i>
                                    </span>
                                @else
                                    <span class="w-6 h-4 bg-amber-500 rounded-sm flex items-center justify-center">
                                        <i class="fas fa-credit-card text-white text-[10px]"></i>
                                    </span>
                                @endif
                                <span class="text-xs font-semibold text-slate-600 capitalize">
                                    {{ str_replace('_', ' ', $tx->payment_method->value) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = match ($tx->status->value) {
                                    'completed' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                    'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                    'failed' => 'bg-rose-100 text-rose-700 border border-rose-200',
                                    'refunded' => 'bg-slate-100 text-slate-700 border border-slate-200',
                                    default => 'bg-slate-100 text-slate-600 border border-slate-200',
                                };
                            @endphp
                            <span class="status-badge {{ $statusClasses }}">
                                {{ $tx->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end">
                                <span class="text-xs font-bold text-slate-700">{{ $tx->created_at->format('M d, Y') }}</span>
                                <span class="text-[10px] text-slate-400">{{ $tx->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-receipt text-3xl text-slate-200"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">No transactions found</h3>
                                <p class="text-slate-500 max-w-xs mx-auto">We couldn't find any financial records matching your criteria. Try adjusting your filters.</p>
                                @if(request()->anyFilled(['search', 'status', 'method']))
                                    <a href="{{ route('admin.transactions.index') }}" class="mt-4 text-brand-blue font-bold text-sm hover:underline">
                                        Clear all filters
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card p-6 rounded-2xl border border-emerald-100">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Completed</p>
                    <h4 class="text-xl font-black text-slate-800">
                        {{ \App\Models\Payment::where('status', \App\Enums\PaymentStatus::COMPLETED)->count() }}
                    </h4>
                </div>
            </div>
        </div>
        
        <div class="glass-card p-6 rounded-2xl border border-amber-100">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active Pending</p>
                    <h4 class="text-xl font-black text-slate-800">
                        {{ \App\Models\Payment::where('status', \App\Enums\PaymentStatus::PENDING)->count() }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 rounded-2xl border border-brand-blue/10">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-brand-blue/10 text-brand-blue rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Volume</p>
                    <h4 class="text-xl font-black text-slate-800">
                        £{{ number_format(\App\Models\Payment::where('status', \App\Enums\PaymentStatus::COMPLETED)->sum('amount'), 2) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
