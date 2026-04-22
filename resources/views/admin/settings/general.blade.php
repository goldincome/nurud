@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
    <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center bg-white rounded-t-xl">
        <div>
            <h2 class="text-xl font-bold text-slate-800">General Settings</h2>
            <p class="text-sm text-slate-500">Manage company information and primary contact details.</p>
        </div>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 border-t-0 rounded-b-xl overflow-hidden">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.settings.general.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Name -->
                <div class="space-y-1">
                    <label for="company_name" class="text-sm font-medium text-slate-700">Company Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="company_name" id="company_name"
                        value="{{ old('company_name', $settings->company_name) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                        placeholder="e.g. Nurud Travel">
                    @error('company_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="space-y-1">
                    <label for="phone_number" class="text-sm font-medium text-slate-700">Phone Number <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="phone_number" id="phone_number"
                        value="{{ old('phone_number', $settings->phone_number) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                        placeholder="e.g. +234 800 123 4567">
                    @error('phone_number')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Support Email -->
                <div class="space-y-1">
                    <label for="support_email" class="text-sm font-medium text-slate-700">Support Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="support_email" id="support_email"
                        value="{{ old('support_email', $settings->support_email) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                        placeholder="e.g. support@nurud.com">
                    @error('support_email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Email -->
                <div class="space-y-1">
                    <label for="contact_email" class="text-sm font-medium text-slate-700">Contact Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" id="contact_email"
                        value="{{ old('contact_email', $settings->contact_email) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                        placeholder="e.g. contact@nurud.com">
                    @error('contact_email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admin Email -->
                <div class="space-y-1">
                    <label for="admin_email" class="text-sm font-medium text-slate-700">Admin Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="admin_email" id="admin_email"
                        value="{{ old('admin_email', $settings->admin_email) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                        placeholder="e.g. admin@nurud.com">
                    @error('admin_email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Additional Emails -->
                <div class="space-y-1 md:col-span-2">
                    <label for="emails" class="text-sm font-medium text-slate-700">Additional Emails (Optional)</label>
                    <input type="text" name="emails" id="emails" value="{{ old('emails', $settings->emails) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue/20 transition-colors placeholder-slate-400 text-sm"
                        placeholder="e.g. sales@nurud.com, hr@nurud.com (comma separated)">
                    <p class="text-[10px] text-slate-400 mt-1">Separate multiple emails with commas.</p>
                    @error('emails')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email Notification Toggles -->
            <div class="pt-6 border-t border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 mb-1">Admin Email Notifications</h3>
                <p class="text-sm text-slate-500 mb-4">Control which events trigger an email notification to the admin.</p>

                <div class="space-y-4">
                    <!-- Toggle 1: New Reservation -->
                    <label for="notify_admin_new_reservation" class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <div>
                            <div class="text-sm font-medium text-slate-800">New Reservation Created</div>
                            <div class="text-xs text-slate-500">Send admin an email when a new flight reservation is made.</div>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="notify_admin_new_reservation" id="notify_admin_new_reservation" value="1"
                                {{ old('notify_admin_new_reservation', $settings->notify_admin_new_reservation) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-brand-blue/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-brand-blue after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </div>
                    </label>

                    <!-- Toggle 2: Stripe Payment Confirmed but Ticket Not Issued -->
                    <label for="notify_admin_stripe_no_ticket" class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <div>
                            <div class="text-sm font-medium text-slate-800">Stripe Payment Confirmed — Ticket Not Issued</div>
                            <div class="text-xs text-slate-500">Send admin an email if Stripe payment is confirmed but ticket issuance fails.</div>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="notify_admin_stripe_no_ticket" id="notify_admin_stripe_no_ticket" value="1"
                                {{ old('notify_admin_stripe_no_ticket', $settings->notify_admin_stripe_no_ticket) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-brand-blue/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-brand-blue after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </div>
                    </label>

                    <!-- Toggle 3: 247 API Down -->
                    <label for="notify_admin_247_api_down" class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <div>
                            <div class="text-sm font-medium text-slate-800">247 Travels API Down</div>
                            <div class="text-xs text-slate-500">Send admin an email if the 247 Travels flight API is unreachable.</div>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="notify_admin_247_api_down" id="notify_admin_247_api_down" value="1"
                                {{ old('notify_admin_247_api_down', $settings->notify_admin_247_api_down) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-brand-blue/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-brand-blue after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </div>
                    </label>

                    <!-- Toggle 4: SimlessPay API Down -->
                    <label for="notify_admin_simlesspay_api_down" class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                        <div>
                            <div class="text-sm font-medium text-slate-800">SimlessPay API Down</div>
                            <div class="text-xs text-slate-500">Send admin an email if the SimlessPay payment API is unreachable.</div>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="notify_admin_simlesspay_api_down" id="notify_admin_simlesspay_api_down" value="1"
                                {{ old('notify_admin_simlesspay_api_down', $settings->notify_admin_simlesspay_api_down) ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:ring-2 peer-focus:ring-brand-blue/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-brand-blue after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm shadow-blue-500/30">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection