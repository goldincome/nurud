@extends('layouts.front')

@section('title', 'Reset Password')

@section('content')
    <main class="min-h-screen py-12 px-4">
        <div class="max-w-md mx-auto">
            <!-- Reset Password Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-textDark mb-2">Reset Password</h1>
                    <p class="text-slate-600">Enter your new password below</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-slate-600 mb-2">Email Address</label>
                        <input id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $request->email) }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                            placeholder="Enter your email">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-slate-600 mb-2">New Password</label>
                        <input id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                            placeholder="Enter new password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-600 mb-2">Confirm Password</label>
                        <input id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                            placeholder="Confirm new password">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-brand-blue hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-colors mb-6">
                        Reset Password
                    </button>

                    <!-- Back to Login Link -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-brand-blue hover:text-sky-700 font-semibold">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
