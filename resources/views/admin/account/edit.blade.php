@extends('layouts.admin')

@section('title', 'My Account')

@section('content')
    <div class="max-w-3xl mx-auto space-y-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Profile Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Profile Information</h3>
                <p class="text-xs text-slate-500 mt-1">Update your name, email, and phone number.</p>
            </div>
            <form action="{{ route('admin.account.update-profile') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5">
                    <div class="flex items-center gap-5 mb-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=002D72&color=fff&size=80"
                            class="w-16 h-16 rounded-full border-2 border-slate-200" alt="Avatar">
                        <div>
                            <p class="font-bold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $user->type->label() }}</p>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone_no" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="phone_no" id="phone_no" value="{{ old('phone_no', $user->phone_no) }}"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @error('phone_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="bg-brand-blue text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-800 transition shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800">Update Password</h3>
                <p class="text-xs text-slate-500 mt-1">Ensure your account is using a strong password.</p>
            </div>
            <form action="{{ route('admin.account.update-password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Current
                            Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm New
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="bg-brand-blue text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-800 transition shadow-sm">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection