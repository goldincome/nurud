@extends('layouts.front')

@section('title', 'Register')

@section('content')
    <main class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Register Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-textDark mb-2">Create Account</h1>
                    <p class="text-slate-600">Join us and start your journey</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-600 mb-2">Title</label>
                            <select id="title" 
                                name="title" 
                                required
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20">
                                <option value="">Select title</option>
                                <option value="Mr" {{ old('title') == 'Mr' ? 'selected' : '' }}>Mr</option>
                                <option value="Mrs" {{ old('title') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                <option value="Ms" {{ old('title') == 'Ms' ? 'selected' : '' }}>Ms</option>
                                <option value="Miss" {{ old('title') == 'Miss' ? 'selected' : '' }}>Miss</option>
                                <option value="Master" {{ old('title') == 'Master' ? 'selected' : '' }}>Master</option>
                                <option value="Dr" {{ old('title') == 'Dr' ? 'selected' : '' }}>Dr</option>
                                <option value="Prof" {{ old('title') == 'Prof' ? 'selected' : '' }}>Prof</option>
                            </select>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-slate-600 mb-2">Gender</label>
                            <select id="gender" 
                                name="gender" 
                                required
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-semibold text-slate-600 mb-2">First Name</label>
                            <input id="first_name" 
                                type="text" 
                                name="first_name" 
                                value="{{ old('first_name') }}" 
                                required 
                                autofocus 
                                autocomplete="given-name"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                                placeholder="Enter your first name">
                            @error('first_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label for="middle_name" class="block text-sm font-semibold text-slate-600 mb-2">Middle Name</label>
                            <input id="middle_name" 
                                type="text" 
                                name="middle_name" 
                                value="{{ old('middle_name') }}" 
                                autocomplete="additional-name"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                                placeholder="Enter your middle name (optional)">
                            @error('middle_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="md:col-span-2">
                            <label for="last_name" class="block text-sm font-semibold text-slate-600 mb-2">Last Name</label>
                            <input id="last_name" 
                                type="text" 
                                name="last_name" 
                                value="{{ old('last_name') }}" 
                                required 
                                autocomplete="family-name"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                                placeholder="Enter your last name">
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Code -->
                        <div>
                            <label for="phone_code" class="block text-sm font-semibold text-slate-600 mb-2">Country Code</label>
                            <select id="phone_code" 
                                name="phone_code" 
                                required
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20">
                                <option value="">Select country code</option>
                                @if(isset($countries))
                                    @foreach($countries as $country)
                                        <option value="{{ $country->full_dialing_code }}" {{ old('phone_code') == $country->full_dialing_code ? 'selected' : '' }}>
                                            {{ $country->full_dialing_code }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('phone_code')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_no" class="block text-sm font-semibold text-slate-600 mb-2">Phone Number</label>
                            <input id="phone_no" 
                                type="tel" 
                                name="phone_no" 
                                value="{{ old('phone_no') }}" 
                                required 
                                autocomplete="tel"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                                placeholder="Enter your phone number">
                            @error('phone_no')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div class="md:col-span-2">
                            <label for="country" class="block text-sm font-semibold text-slate-600 mb-2">Country</label>
                            <select id="country" 
                                name="country" 
                                required
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20">
                                <option value="">Select your country</option>
                                @if(isset($countries))
                                    @foreach($countries as $country)
                                        <option value="{{ $country->name }}" {{ old('country') == $country->name ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('country')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-semibold text-slate-600 mb-2">Email Address</label>
                            <input id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="username"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                                placeholder="Enter your email">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-600 mb-2">Password</label>
                            <input id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                                placeholder="Create a password">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-600 mb-2">Confirm Password</label>
                            <input id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                                placeholder="Confirm your password">
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-brand-blue hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-colors mt-8 mb-6">
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-slate-600">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-brand-blue hover:text-sky-700 font-semibold">
                                Sign in
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
