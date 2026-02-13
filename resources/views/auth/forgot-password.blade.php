@extends('layouts.front')

@section('title', 'Forgot Password')

@section('content')
    <main class="min-h-screen py-12 px-4">
        <div class="max-w-md mx-auto">
            <!-- Forgot Password Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-textDark mb-2">Forgot Password?</h1>
                    <p class="text-slate-600">No problem. Just let us know your email address and we will email you a password reset link.</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 rounded bg-green-50 text-green-700 border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
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
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                            placeholder="Enter your email">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-brand-blue hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-colors mb-6">
                        Email Password Reset Link
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
