@extends('layouts.admin')
@section('title', 'Manage Subscribers')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('warning'))
        <div class="bg-amber-100 border-l-4 border-amber-500 text-amber-800 p-4 rounded-md shadow-md">
            {{ session('warning') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session('failed_retry'))
        <div class="bg-white border text-slate-800 p-4 rounded-md shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <p class="font-bold text-brand-red">Failed Sent Notice</p>
                <p class="text-sm text-slate-500">There were {{ count(session('failed_retry')['ids']) }} subscribers that failed to receive the previous email. Click the button to retry.</p>
            </div>
            <form action="{{ route('admin.subscribers.send-email') }}" method="POST">
                @csrf
                <input type="hidden" name="send_to_all" value="0">
                <input type="hidden" name="subject" value="{{ session('failed_retry')['subject'] }}">
                <input type="hidden" name="message" value="{{ session('failed_retry')['message'] }}">
                @foreach(session('failed_retry')['ids'] as $failedId)
                    <input type="hidden" name="recipients[]" value="{{ $failedId }}">
                @endforeach
                <button type="submit" class="bg-brand-red hover:bg-red-800 text-white px-4 py-2 rounded-lg font-bold text-sm transition-colors whitespace-nowrap">
                    <i class="fas fa-redo mr-2"></i> Resend to Failed Ones
                </button>
            </form>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <h3 class="text-slate-500 text-sm font-medium mb-1">Total Active</h3>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($totalSubscribers) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <h3 class="text-slate-500 text-sm font-medium mb-1">Today</h3>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($today) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <h3 class="text-slate-500 text-sm font-medium mb-1">This Week</h3>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($thisWeek) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <h3 class="text-slate-500 text-sm font-medium mb-1">This Month</h3>
            <p class="text-3xl font-bold text-slate-800">{{ number_format($monthly) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
            <h3 class="text-slate-500 text-sm font-medium mb-1">Unsubscribed</h3>
            <p class="text-3xl font-bold text-brand-red">{{ number_format($totalUnsubscribers) }}</p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Table -->
        <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-800">Subscribers List</h3>
            </div>
            
            <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-sm border-b border-slate-200">
                                <th class="p-4 py-3 font-medium text-center w-12">
                                    <input type="checkbox" id="select-all" class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue cursor-pointer">
                                </th>
                                <th class="p-4 py-3 font-medium">Email Address</th>
                                <th class="p-4 py-3 font-medium">Status</th>
                                <th class="p-4 py-3 font-medium">Subscribed Date</th>
                                <th class="p-4 py-3 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            @forelse($subscribers as $subscriber)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-center">
                                        <input type="checkbox" name="recipients[]" value="{{ $subscriber->id }}" 
                                            class="subscriber-checkbox rounded border-slate-300 text-brand-blue focus:ring-brand-blue cursor-pointer"
                                            {{ !$subscriber->is_subscribed ? 'disabled' : '' }}>
                                    </td>
                                    <td class="p-4 font-medium text-slate-800">{{ $subscriber->email }}</td>
                                    <td class="p-4">
                                        @if($subscriber->is_subscribed)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                Unsubscribed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-slate-500">{{ $subscriber->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="p-4">
                                        <form action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Delete Subscriber">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500">
                                        <i class="fas fa-envelope-open-text text-4xl text-slate-300 mb-3 block"></i>
                                        No subscribers found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-200">
                    {{ $subscribers->links() }}
                </div>
        </div>

        <div class="w-full lg:w-96 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-max sticky top-6">
            <form id="bulk-email-form" action="{{ route('admin.subscribers.send-email') }}" method="POST">
                @csrf
                <div class="p-6 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-800"><i class="fas fa-paper-plane mr-2 text-brand-orange"></i> Compose Email</h3>
                </div>
            <div class="p-6 space-y-4">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Target Audience</label>
                    <div class="space-y-2 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="send_to_all" value="0" class="text-brand-blue" checked id="send-selected">
                            <span class="ml-2 text-sm text-slate-700">Selected Checkboxes (<span id="selected-count">0</span>)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="send_to_all" value="1" class="text-brand-blue">
                            <span class="ml-2 text-sm text-slate-700">All Active Subscribers ({{ $totalSubscribers }})</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email Subject</label>
                    <input type="text" name="subject" required class="w-full p-2 border border-slate-300 rounded-lg focus:ring-brand-blue focus:border-brand-blue outline-none" placeholder="Enter subject line">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email Message</label>
                    <textarea name="message" rows="8" required class="w-full p-2 border border-slate-300 rounded-lg focus:ring-brand-blue focus:border-brand-blue outline-none" placeholder="Write your newsletter content here..."></textarea>
                </div>

                <button type="submit" id="send-email-btn" class="w-full bg-brand-blue text-white py-3 rounded-lg font-bold hover:bg-blue-900 transition-colors disabled:opacity-50">
                    Send Email
                </button>
            </div>
            </form>
        </div>

    </div>

</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all');
        const subscriberCheckboxes = document.querySelectorAll('.subscriber-checkbox');
        const selectedCountSpan = document.getElementById('selected-count');
        const sendSelectedRadio = document.getElementById('send-selected');
        const sendAllRadio = document.querySelector('input[name="send_to_all"][value="1"]');
        const sendEmailBtn = document.getElementById('send-email-btn');

        function updateSelectedCount() {
            const count = document.querySelectorAll('.subscriber-checkbox:checked').length;
            selectedCountSpan.textContent = count;
            
            // if send_to_all is false and selected count is 0, disable send button
            if (sendSelectedRadio.checked && count === 0) {
                sendEmailBtn.disabled = true;
            } else {
                sendEmailBtn.disabled = false;
            }
        }

        if(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                subscriberCheckboxes.forEach(cb => {
                    if(!cb.disabled) {
                        cb.checked = selectAllCheckbox.checked;
                    }
                });
                if(selectAllCheckbox.checked) sendSelectedRadio.checked = true;
                updateSelectedCount();
            });
        }

        subscriberCheckboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                sendSelectedRadio.checked = true;
                updateSelectedCount();
            });
        });

        // Watch radio changes to enable/disable button appropriately
        document.querySelectorAll('input[name="send_to_all"]').forEach(radio => {
            radio.addEventListener('change', updateSelectedCount);
        });

        // Initial check
        updateSelectedCount();

        const bulkForm = document.getElementById('bulk-email-form');
        if(bulkForm) {
            bulkForm.addEventListener('submit', function(e) {
                if (sendSelectedRadio.checked) {
                    const selected = document.querySelectorAll('.subscriber-checkbox:checked');
                    selected.forEach(cb => {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'recipients[]';
                        hiddenInput.value = cb.value;
                        bulkForm.appendChild(hiddenInput);
                    });
                }
            });
        }
    });
</script>
@endsection
