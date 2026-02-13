@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-blue-50 text-brand-blue rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span class="text-green-500 text-xs font-bold bg-green-50 px-2 py-1 rounded">+12%</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Bookings</h3>
            <p class="text-2xl font-bold text-slate-800">1,284</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-orange-50 text-brand-orange rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <span class="text-slate-500 text-xs font-medium">On Hold</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Pending Payments</h3>
            <p class="text-2xl font-bold text-slate-800">43</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="text-green-500 text-xs font-bold bg-green-50 px-2 py-1 rounded">+5.4%</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">Total Revenue</h3>
            <p class="text-2xl font-bold text-slate-800">₦14,240,500</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-user-plus"></i>
                </div>
                <span class="text-blue-500 text-xs font-bold bg-blue-50 px-2 py-1 rounded">New</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium">New Customers</h3>
            <p class="text-2xl font-bold text-slate-800">156</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Recent Bookings</h3>
                <a href="#" class="text-xs font-bold text-brand-blue hover:underline uppercase tracking-wider">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4">ID / Customer</th>
                            <th class="px-6 py-4">Route</th>
                            <th class="px-6 py-4">Amount</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">VN260213-44104</div>
                                <div class="text-xs text-slate-400">ifylovely@mailinator.com</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">LOS - CDG</td>
                            <td class="px-6 py-4 font-bold">₦4,967,342</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-600 uppercase italic">On
                                    Hold</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-slate-400 hover:text-brand-blue"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-brand-blue text-white rounded-xl p-6 shadow-lg shadow-blue-900/20 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-2">Need Help?</h3>
                    <p class="text-sm text-blue-100 mb-6">Access the administrative guide or contact system support.</p>
                    <button
                        class="bg-brand-orange hover:bg-white hover:text-brand-blue text-white font-bold py-2 px-6 rounded-lg text-sm transition-all">
                        System Guide
                    </button>
                </div>
                <i class="fas fa-headset absolute -bottom-4 -right-4 text-white/10 text-8xl"></i>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4">Quick Links</h3>
                <div class="space-y-3">
                    <button
                        class="w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg text-sm font-medium text-slate-600 transition-colors">
                        <span>Manage Flight API</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                    <button
                        class="w-full flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg text-sm font-medium text-slate-600 transition-colors">
                        <span>Voucher Settings</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection