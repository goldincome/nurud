@extends('layouts.customer')

@section('title', 'My Profile')

@section('customer_content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Profile Settings</h1>
            <p class="text-slate-500">View and update your personal information.</p>
        </div>

        <!-- Personal Information Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-brand-blue text-white flex items-center justify-center">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <h2 class="font-bold text-slate-800">Personal Information</h2>
            </div>
            <div class="p-8">
                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label for="first_name" class="text-xs font-bold text-slate-500 uppercase tracking-wider">First
                                Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-user-tag text-sm"></i>
                                </span>
                                <input type="text" name="first_name" id="first_name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-slate-700 font-medium focus:ring-2 focus:ring-brand-blue/20 focus:bg-white focus:border-brand-blue transition-all"
                                    required>
                            </div>
                            @error('first_name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label for="last_name" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Last
                                Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-user-tag text-sm"></i>
                                </span>
                                <input type="text" name="last_name" id="last_name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-slate-700 font-medium focus:ring-2 focus:ring-brand-blue/20 focus:bg-white focus:border-brand-blue transition-all"
                                    required>
                            </div>
                            @error('last_name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email
                                Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-slate-700 font-medium focus:ring-2 focus:ring-brand-blue/20 focus:bg-white focus:border-brand-blue transition-all"
                                    required>
                            </div>
                            @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Phone
                                Number</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="fas fa-phone text-sm"></i>
                                </span>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-transparent rounded-xl text-slate-700 font-medium focus:ring-2 focus:ring-brand-blue/20 focus:bg-white focus:border-brand-blue transition-all">
                            </div>
                            @error('phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-brand-blue text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-brand-blue/20 hover:bg-sky-700 transform hover:-translate-y-0.5 transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection