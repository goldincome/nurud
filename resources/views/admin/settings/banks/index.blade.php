@extends('layouts.admin')

@section('title', 'Admin Bank Management')

@section('content')
    <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center bg-white rounded-t-xl">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Bank Accounts</h2>
            <p class="text-sm text-slate-500">Manage bank accounts available for manual payments.</p>
        </div>
        <a href="{{ route('admin.banks.create') }}"
            class="bg-brand-blue text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Add Bank Account
        </a>
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
                        <th class="px-6 py-4">Bank Name</th>
                        <th class="px-6 py-4">Account Name</th>
                        <th class="px-6 py-4">Account Number</th>
                        <th class="px-6 py-4">Country</th>
                        <th class="px-6 py-4">Deadline</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($banks as $bank)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $bank->bank_name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $bank->account_name }}</td>
                            <td class="px-6 py-4 font-mono text-slate-600">{{ $bank->account_number }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($bank->country)
                                    {{ $bank->country->name }} ({{ $bank->country->iso_alpha2 }})
                                @else
                                    <span class="text-slate-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <span
                                    class="bg-orange-100 text-brand-orange px-2 py-1 rounded text-xs font-bold">{{ $bank->deadline }}
                                    hrs</span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.banks.edit', $bank->id) }}"
                                    class="text-slate-400 hover:text-brand-blue transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this bank account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-university text-4xl text-slate-200 mb-2"></i>
                                    <p>No bank accounts found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection