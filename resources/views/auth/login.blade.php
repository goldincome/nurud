@extends('layouts.front')

@section('title', 'Login')

@section('content')
    <main class="min-h-screen py-12 px-4">
        <div class="max-w-md mx-auto">
            <!-- Login Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-textDark mb-2">Welcome Back</h1>
                    <p class="text-slate-600">Sign in to continue to your account</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 rounded bg-green-50 text-green-700 border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-slate-600 mb-2">Email Address</label>
                        <input id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
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
                        <label for="password" class="block text-sm font-semibold text-slate-600 mb-2">Password</label>
                        <input id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                            placeholder="Enter your password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label for="remember" class="flex items-center cursor-pointer">
                            <input id="remember" type="checkbox" name="remember" 
                                class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue">
                            <span class="ml-2 text-sm text-slate-600">Remember me</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                                class="text-sm text-brand-blue hover:text-sky-700 font-medium">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-brand-blue hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-colors">
                        Sign In
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-slate-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-brand-blue hover:text-sky-700 font-semibold">
                            Create one
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection
