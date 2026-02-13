@extends('layouts.admin')

@section('title', 'Add Bank Account')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center space-x-2 mb-6 text-sm text-slate-500">
            <a href="{{ route('admin.banks.index') }}" class="hover:text-brand-blue transition">My Banks</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Add New</span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-lg font-bold text-slate-800">Add New Bank Account</h2>
                <p class="text-xs text-slate-500 mt-1">Fill in the details to add a new bank account for payments.</p>
            </div>

            <form action="{{ route('admin.banks.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Bank Name -->
                    <div class="space-y-1">
                        <label for="bank_name" class="text-sm font-medium text-slate-700">Bank Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="e.g. Chase Bank">
                        @error('bank_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Number -->
                    <div class="space-y-1">
                        <label for="account_number" class="text-sm font-medium text-slate-700">Account Number <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="e.g. 1234567890">
                        @error('account_number')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Name -->
                    <div class="space-y-1 md:col-span-2">
                        <label for="account_name" class="text-sm font-medium text-slate-700">Account Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="account_name" id="account_name" value="{{ old('account_name') }}"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="e.g. ACME Travel Services Ltd.">
                        @error('account_name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="space-y-1">
                        <label for="country_id" class="text-sm font-medium text-slate-700">Country <span
                                class="text-red-500">*</span></label>
                        <select name="country_id" id="country_id"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors text-sm">
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }} ({{ $country->iso_alpha2 }})
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deadline -->
                    <div class="space-y-1">
                        <label for="deadline" class="text-sm font-medium text-slate-700">Payment Deadline (Hours) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="deadline" id="deadline" value="{{ old('deadline', 24) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="e.g. 24">
                        <p class="text-[10px] text-slate-400 mt-1">Time allowed for customer to make payment.</p>
                        @error('deadline')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions -->
                    <div class="space-y-1 md:col-span-2">
                        <label for="instructions" class="text-sm font-medium text-slate-700">Instructions (Optional)</label>
                        <textarea name="instructions" id="instructions" rows="4"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="Enter any specific payment instructions or notes for the customer...">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                    <a href="{{ route('admin.banks.index') }}"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 text-sm font-medium hover:bg-slate-50 transition">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm shadow-blue-500/30">
                        Create Bank Account
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection