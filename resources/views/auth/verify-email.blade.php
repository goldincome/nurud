@extends('layouts.front')

@section('title', 'Verify Email')

@section('content')
    <main class="min-h-screen py-12 px-4">
        <div class="max-w-md mx-auto">
            <!-- Verify Email Card -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-brand-textDark mb-2">Verify Your Email</h1>
                    <p class="text-slate-600">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 rounded bg-green-50 text-green-700 border border-green-200">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <!-- Resend Verification Email Button -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-brand-blue hover:bg-sky-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-colors">
                        Resend Verification Email
                    </button>
                </form>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="w-full text-slate-600 hover:text-brand-blue font-medium py-2">
                        <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection
