@extends('layouts.front')

@section('title', 'Confirm Password')

@section('content')
    <main class="min-h-screen py-12 px-4">
        <div class="max-w-md mx-auto">
            <!-- Confirm Password Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-textDark mb-2">Confirm Password</h1>
                    <p class="text-slate-600">This is a secure area of the application. Please confirm your password before continuing.</p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

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

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-brand-blue hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-colors">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection
