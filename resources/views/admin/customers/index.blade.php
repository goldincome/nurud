@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-blue-50 text-brand-blue rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Customers</h3>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalCustomers) }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span class="text-blue-500 text-xs font-bold bg-blue-50 px-2 py-1 rounded">This Month</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">New Customers</h3>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($newCustomersThisMonth) }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Revenue from Customers</h3>
            <p class="text-2xl font-bold text-slate-800">₦{{ number_format($totalRevenue) }}</p>
        </div>
    </div>

    {{-- Customer List --}}
    <div
        class="px-6 py-6 border-b border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center bg-white rounded-t-xl gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Customers</h2>
            <p class="text-sm text-slate-500">View and manage all registered customers.</p>
        </div>
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..."
                class="border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 w-full md:w-64">
            <button type="submit"
                class="bg-brand-blue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition whitespace-nowrap">
                <i class="fas fa-search mr-1"></i> Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}"
                    class="bg-slate-100 text-slate-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-200 transition whitespace-nowrap">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 border-t-0 rounded-b-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead
                    class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">
                            <a href="{{ route('admin.customers.index', array_merge(request()->except('page'), ['sort' => 'name', 'dir' => request('sort') === 'name' && request('dir') === 'asc' ? 'desc' : 'asc'])) }}"
                                class="hover:text-brand-blue inline-flex items-center gap-1">
                                Customer
                                @if(request('sort') === 'name')
                                    <i class="fas fa-sort-{{ request('dir') === 'asc' ? 'up' : 'down' }} text-brand-blue"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4">Email / Phone</th>
                        <th class="px-6 py-4 text-center">
                            <a href="{{ route('admin.customers.index', array_merge(request()->except('page'), ['sort' => 'bookings_count', 'dir' => request('sort') === 'bookings_count' && request('dir') === 'desc' ? 'asc' : 'desc'])) }}"
                                class="hover:text-brand-blue inline-flex items-center gap-1">
                                Bookings
                                @if(request('sort') === 'bookings_count')
                                    <i class="fas fa-sort-{{ request('dir') === 'asc' ? 'up' : 'down' }} text-brand-blue"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4">
                            <a href="{{ route('admin.customers.index', array_merge(request()->except('page'), ['sort' => 'bookings_sum_total_price', 'dir' => request('sort') === 'bookings_sum_total_price' && request('dir') === 'desc' ? 'asc' : 'desc'])) }}"
                                class="hover:text-brand-blue inline-flex items-center gap-1">
                                Total Spent
                                @if(request('sort') === 'bookings_sum_total_price')
                                    <i class="fas fa-sort-{{ request('dir') === 'asc' ? 'up' : 'down' }} text-brand-blue"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4">
                            <a href="{{ route('admin.customers.index', array_merge(request()->except('page'), ['sort' => 'created_at', 'dir' => request('sort', 'created_at') === 'created_at' && request('dir', 'desc') === 'desc' ? 'asc' : 'desc'])) }}"
                                class="hover:text-brand-blue inline-flex items-center gap-1">
                                Joined
                                @if(request('sort', 'created_at') === 'created_at')
                                    <i
                                        class="fas fa-sort-{{ request('dir', 'desc') === 'asc' ? 'up' : 'down' }} text-brand-blue"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->first_name . ' ' . $customer->last_name) }}&background=002D72&color=fff&size=36"
                                        class="w-9 h-9 rounded-full" alt="">
                                    <div>
                                        <div class="font-medium text-slate-800">
                                            {{ $customer->first_name ?? '' }} {{ $customer->last_name ?? '' }}
                                            @if(!$customer->first_name && !$customer->last_name)
                                                {{ $customer->name }}
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-400 uppercase font-bold tracking-wide">
                                            {{ $customer->city ?? '' }}{{ $customer->city && $customer->country ? ', ' : '' }}{{ $customer->country ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-600">{{ $customer->email }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $customer->phone_code ?? '' }}{{ $customer->phone_no ?? 'No phone' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-full text-xs font-bold
                                                    {{ $customer->bookings_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $customer->bookings_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800">
                                @if($customer->bookings_sum_total_price > 0)
                                    ₦{{ number_format($customer->bookings_sum_total_price) }}
                                @else
                                    <span class="text-slate-400">₦0</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $customer->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.customers.show', $customer->id) }}"
                                    class="inline-flex items-center gap-1 text-brand-blue hover:text-blue-700 font-medium text-xs transition">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users-slash text-4xl text-slate-200 mb-3"></i>
                                    <p class="font-medium">No customers found.</p>
                                    @if(request('search'))
                                        <p class="text-xs text-slate-400 mt-1">Try adjusting your search criteria.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
@endsection