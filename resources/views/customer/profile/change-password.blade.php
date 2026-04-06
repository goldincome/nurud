@extends('layouts.customer')

@section('title', 'Change Password')

@section('customer_content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Security</h1>
            <p class="text-slate-500">Ensure your account is secure by using a strong password.</p>
        </div>

        <!-- Password Card -->
        <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-brand-orange text-white flex items-center justify-center">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <h2 class="font-bold text-slate-800">Change Password</h2>
            </div>
            <div class="p-8">
                <form action="{{ route('customer.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6 mb-8">
                        <!-- Current Password -->
                        <div class="space-y-2">
                            <label for="current_password"
                                class="text-xs font-bold text-slate-500 uppercase tracking-wider">Current Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-key text-sm"></i>
                                </span>
                                <input type="password" name="current_password" id="current_password"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-slate-700 font-medium focus:ring-2 focus:ring-brand-blue/20 focus:bg-white focus:border-brand-blue transition-all"
                                    required>
                            </div>
                            @error('current_password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- New Password -->
                        <div class="space-y-2">
                            <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider">New
                                Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-lock text-sm"></i>
                                </span>
                                <input type="password" name="password" id="password"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-slate-700 font-medium focus:ring-2 focus:ring-brand-blue/20 focus:bg-white focus:border-brand-blue transition-all"
                                    required>
                            </div>
                            @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="space-y-2">
                            <label for="password_confirmation"
                                class="text-xs font-bold text-slate-500 uppercase tracking-wider">Confirm New
                                Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-shield-alt text-sm"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-slate-700 font-medium focus:ring-2 focus:ring-brand-blue/20 focus:bg-white focus:border-brand-blue transition-all"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-brand-orange text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-brand-orange/20 hover:bg-orange-600 transform hover:-translate-y-0.5 transition-all">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection