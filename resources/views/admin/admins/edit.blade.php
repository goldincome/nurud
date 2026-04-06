@extends('layouts.admin')

@section('title', 'Edit Admin User')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center space-x-2 mb-6 text-sm text-slate-500">
            <a href="{{ route('admin.admins.index') }}" class="hover:text-brand-blue transition">Admins</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Edit: {{ $admin->name }}</span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h2 class="text-lg font-bold text-slate-800">Edit Admin User</h2>
                <p class="text-xs text-slate-500 mt-1">Update administrator details.</p>
            </div>

            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-1">
                        <label for="name" class="text-sm font-medium text-slate-700">Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="Full Name">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="text-sm font-medium text-slate-700">Email Address <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="Email Address">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1">
                        <label for="phone_no" class="text-sm font-medium text-slate-700">Phone Number</label>
                        <input type="text" name="phone_no" id="phone_no" value="{{ old('phone_no', $admin->phone_no) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                            placeholder="Optional">
                        @error('phone_no')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2 border-t border-slate-100 pt-4 mt-2">
                        <h3 class="text-sm font-bold text-slate-800 mb-4">Change Password <span
                                class="text-xs font-normal text-slate-500">(Leave blank to keep current)</span></h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div class="space-y-1">
                                <label for="password" class="text-sm font-medium text-slate-700">New Password</label>
                                <input type="password" name="password" id="password"
                                    class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                                    placeholder="******">
                                @error('password')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-1">
                                <label for="password_confirmation" class="text-sm font-medium text-slate-700">Confirm New
                                    Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                                    placeholder="******">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                    <a href="{{ route('admin.admins.index') }}"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 text-sm font-medium hover:bg-slate-50 transition">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm shadow-blue-500/30">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection