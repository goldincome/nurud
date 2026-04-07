@extends('layouts.front')

@section('content')
    <!-- Progress Bar Section -->
    <div class="bg-brand-blue py-8">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="relative flex items-center justify-between w-full">
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-blue-900 z-0"></div>
                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-3/4 h-1 bg-brand-orange z-0"></div>

                <div class="relative z-10 flex flex-col items-center step-completed">
                    <div
                        class="step-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-md">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-xs text-brand-orange font-medium">Search flight</span>
                </div>

                <div class="relative z-10 flex flex-col items-center step-completed">
                    <div
                        class="step-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-md">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-xs text-brand-orange font-medium">Traveler Details</span>
                </div>

                <div class="relative z-10 flex flex-col items-center step-active">
                    <div
                        class="step-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-md">
                        3
                    </div>
                    <span class="text-xs text-white font-medium">Make payment</span>
                </div>

                <div class="relative z-10 flex flex-col items-center step-inactive">
                    <div
                        class="step-circle w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-sm mb-2">
                        4
                    </div>
                    <span class="text-xs text-slate-300 font-medium">Confirmation</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">

        <h1 class="text-2xl font-bold text-slate-800 mb-1">Payment Details</h1>
        <p class="text-sm text-slate-500 mb-6">Select a payment method below</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                <div class="flex flex-wrap gap-2 bg-white p-2 rounded-full border border-slate-100 shadow-sm w-fit">
                    <button onclick="switchTab('hold')" id="btn-hold"
                        class="px-6 py-2 rounded-full text-sm font-bold transition-all bg-white text-brand-blue hover:bg-slate-50">
                        Book on hold
                    </button>
                    <button onclick="switchTab('card')" id="btn-card"
                        class="px-6 py-2 rounded-full text-sm font-bold transition-all bg-brand-blue text-white shadow-md">
                        Pay with Card
                    </button>
                    <button onclick="switchTab('transfer')" id="btn-transfer"
                        class="px-6 py-2 rounded-full text-sm font-bold transition-all bg-white text-brand-blue hover:bg-slate-50">
                        Pay with Transfer
                    </button>
                    <button onclick="switchTab('bnpl')" id="btn-bnpl"
                        class="px-6 py-2 rounded-full text-sm font-bold transition-all bg-white text-brand-blue hover:bg-slate-50">
                        Buy now, Pay later
                    </button>
                </div>

                <div id="payment-content"
                    class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden min-h-[400px]">

                    <div id="tab-transfer" class="hidden animate-fade-in">
                        <div class="bg-brand-blue px-6 py-4">
                            <h2 class="text-white font-bold text-lg">Pay with Bank Transfer</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                                The quoted prices are not guaranteed until payment has been confirmed by Nurud.
                                We accept payments by bank transfer or cash deposits into the below listed bank accounts.
                            </p>

                            <div class="overflow-x-auto rounded-lg border border-slate-200">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                                        <tr>
                                            <th class="px-4 py-4">Name</th>
                                            <th class="px-4 py-4">IBAN / Acct No.</th>
                                            <th class="px-4 py-4">Swift</th>
                                            <th class="px-4 py-4">Bank Name</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($banks as $bank)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-4 text-slate-600">{{ $bank->account_name }}</td>
                                                <td class="px-4 py-4 text-slate-600 font-medium">
                                                    {{ $bank->iban ?: $bank->account_number }}
                                                    @if($bank->routing_number)<div class="text-[10px] text-slate-400">Routing: {{ $bank->routing_number }}</div>@endif
                                                </td>
                                                <td class="px-4 py-4 text-slate-600">{{ $bank->swift_code ?: '-' }}</td>
                                                <td class="px-4 py-4 font-medium text-slate-700">{{ $bank->bank_name }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-slate-500">No bank accounts available.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 text-center">
                                <form method="POST" action="{{ route('bookings.store') }}">
                                    @csrf
                                    <input type="hidden" name="booking_type" value="bank_transfer">
                                    <button type="submit" class="bg-brand-blue hover:bg-blue-900 text-white font-bold py-3 px-10 rounded-full shadow-md transition-colors">
                                        Confirm Reservation
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="tab-card" class="block animate-fade-in">
                        <div class="bg-brand-blue px-6 py-4">
                            <h2 class="text-white font-bold text-lg">Pay with Card</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-slate-600 mb-6">
                                The quoted prices are not guaranteed until payment has been confirmed by NURUD.
                                See all the preferred and acceptable payment method types listed below.
                            </p>

                            <div class="space-y-6 mb-6">
                                <!-- Stripe Section -->
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-purple-600 rounded flex items-center justify-center text-white font-bold text-xs">
                                                Stripe</div>
                                            <div>
                                                <h3 class="font-bold text-slate-800">Pay with Stripe</h3>
                                                <p class="text-xs text-slate-500">Secure card payment via Stripe (Global)
                                                </p>
                                            </div>
                                        </div>
                                        <form action="{{ route('stripe.checkout') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $booking->id ?? '' }}">
                                            <button type="submit"
                                                class="bg-brand-orange hover:bg-brand-orangeHover text-white font-bold py-2 px-6 rounded-full shadow-sm transition-colors text-sm">
                                                Pay with Stripe
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-xs text-slate-500 ml-13">Visa, Mastercard, American Express and more.</p>
                                </div>

                                <!-- Paystack Section -->
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-green-500 rounded flex items-center justify-center text-white font-bold text-xs">
                                                Paystack</div>
                                            <div>
                                                <h3 class="font-bold text-slate-800">Pay with Paystack</h3>
                                                <p class="text-xs text-slate-500">Secure card payment via Paystack
                                                    (Local/Africa)</p>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('bookings.store') }}">
                                            @csrf
                                            <input type="hidden" name="booking_type" value="paystack">
                                            <button type="submit"
                                                class="bg-brand-blue hover:bg-blue-900 text-white font-bold py-2 px-6 rounded-full shadow-sm transition-colors text-sm">
                                                Pay with Card
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-xs text-slate-500 ml-13">Mastercard, Visa, and Verve accepted.</p>
                                </div>
                            </div>

                            <table class="w-full text-sm text-left border border-slate-200 rounded-lg overflow-hidden mb-6">
                                <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Payment Channel</th>
                                        <th class="px-4 py-3">Booking Amount</th>
                                        <th class="px-4 py-3">Transaction Charge</th>
                                        <th class="px-4 py-3 text-right">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-4 font-medium">Paystack</td>
                                        <td class="px-4 py-4 text-slate-600">₦2,420,925</td>
                                        <td class="px-4 py-4 text-slate-600">₦2,000</td>
                                        <td class="px-4 py-4 text-right font-bold text-brand-blue">₦2,422,925</td>
                                    </tr>
                                </tbody>
                            </table>


                        </div>
                    </div>

                    <div id="tab-hold" class="hidden animate-fade-in">
                        <div class="bg-brand-blue px-6 py-4">
                            <h2 class="text-white font-bold text-lg">Book on Hold</h2>
                        </div>
                        <div class="p-6 text-center py-12">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clock text-brand-blue text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Hold your booking for 12 hours</h3>
                            <p class="text-sm text-slate-600 max-w-md mx-auto mb-8">
                                Lock in this price now and pay later. Your booking will be cancelled automatically if
                                payment is not received within 12 hours. We accept payments by bank transfer or cash
                                deposits into the below listed bank accounts.
                            </p>
                            <div class="overflow-x-auto rounded-lg border border-slate-200">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                                        <tr>
                                            <th class="px-4 py-4">Name</th>
                                            <th class="px-4 py-4">IBAN / Acct No.</th>
                                            <th class="px-4 py-4">Swift</th>
                                            <th class="px-4 py-4">Bank Name</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($banks as $bank)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-4 text-slate-600 border-b border-gray-100">{{ $bank->account_name }}</td>
                                                <td class="px-4 py-4 text-slate-600 border-b border-gray-100 font-medium">
                                                    {{ $bank->iban ?: $bank->account_number }}
                                                    @if($bank->routing_number)<div class="text-[10px] text-slate-400">Routing: {{ $bank->routing_number }}</div>@endif
                                                </td>
                                                <td class="px-4 py-4 text-slate-600 border-b border-gray-100">{{ $bank->swift_code ?: '-' }}</td>
                                                <td class="px-4 py-4 font-medium text-slate-700 border-b border-gray-100">{{ $bank->bank_name }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-slate-500 border-b border-gray-100">No bank accounts available.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div> </br>
                            <form method="POST" action="{{ route('bookings.store') }}">
                                @csrf
                                <input type="hidden" name="booking_type" value="on_hold">
                                <button type="submit"
                                    class="bg-brand-blue hover:bg-blue-900 text-white font-bold py-3 px-10 rounded-full shadow-md transition-colors">
                                    Confirm Booking on Hold
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="tab-bnpl" class="hidden animate-fade-in">
                        <div class="bg-brand-blue px-6 py-4">
                            <h2 class="text-white font-bold text-lg">Buy Now, Pay Later</h2>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-6">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-handshake text-brand-blue text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 mb-2">Flexible Credit Facility</h3>
                                <p class="text-sm text-slate-600 max-w-md mx-auto mb-6">
                                    Take advantage of our exclusive Credit Facility to secure your flight immediately while you spread the payments. 
                                    With Nurud's BNPL, you don't have to miss out on great fares.
                                </p>
                                <button type="button" onclick="openBnplModal()" class="text-brand-orange hover:text-orange-700 underline text-sm font-bold mb-8 block mx-auto">
                                    Read Terms & Conditions
                                </button>
                                
                                <form method="POST" action="{{ route('bookings.store') }}">
                                    @csrf
                                    <input type="hidden" name="booking_type" value="pay_later">
                                    <button type="button" onclick="openBnplModal()" class="bg-brand-blue hover:bg-blue-900 text-white font-bold py-3 px-10 rounded-full shadow-md transition-colors">
                                        Kick Start Process
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col md:flex-row justify-between items-center mt-4 gap-4">
                    <p class="text-xs text-slate-500">
                        By booking and payment, you are agreeing to the <a href="#" class="text-brand-blue underline">terms
                            & conditions</a>
                    </p>

                </div>

            </div>

            <aside class="lg:col-span-1 space-y-6">

                <!-- Booking Summary Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                    <!-- Image Header -->
                    <div class="h-32 bg-slate-200 relative">
                        <img src="https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=2070&auto=format&fit=crop"
                            class="w-full h-full object-cover" alt="Destination">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>

                    <div class="p-5">
                        <h3 class="font-bold text-lg text-brand-textDark mb-1">Booking Summary</h3>
                        <div class="w-10 h-1 bg-brand-orange mb-4"></div>

                        @if(isset($flightData['itineraries']) && count($flightData['itineraries']) > 0)
                            @foreach($flightData['itineraries'] as $itineraryIndex => $itinerary)
                                @if($itineraryIndex > 0)
                                <div class="border-t border-dashed border-slate-200 my-4"></div> @endif

                                <div class="mb-6">
                                    @if(
                                                    isset($itinerary['segments'][0]['segmentDeparture']['airport']['city']) &&
                                                    isset($itinerary['segments'][count($itinerary['segments']) -
                                                        1]['segmentArrival']['airport']['city'])
                                                )
                                                <h4 class="font-bold text-md text-brand-textDark">
                                                    {{ $itinerary['segments'][0]['segmentDeparture']['airport']['city'] }} -
                                                    {{ $itinerary['segments'][count($itinerary['segments']) -
                                        1]['segmentArrival']['airport']['city'] }}
                                                </h4>
                                    @endif
                                    @if(
                                            isset($itinerary['segments'][0]['segmentDeparture']['at']) &&
                                            isset($itinerary['segments'][count($itinerary['segments']) - 1]['segmentArrival']['at'])
                                        )
                                        @php
                                            $startDate =
                                                \Carbon\Carbon::parse($itinerary['segments'][0]['segmentDeparture']['at'])->format('M d');
                                            $endDate = \Carbon\Carbon::parse($itinerary['segments'][count($itinerary['segments']) -
                                                1]['segmentArrival']['at'])->format('M d');
                                        @endphp
                                        <p class="text-xs text-slate-600">{{ $startDate }} - {{ $endDate }}</p>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <span class="bg-brand-blue text-white text-[10px] font-bold px-2 py-1 rounded">
                                        {{ $itinerary['itineraryTitle'] }}
                                    </span>
                                    <div class="flex justify-between mt-2">
                                        <div>
                                            @if(isset($itinerary['segments'][0]['segmentDeparture']['at']))
                                                @php
                                                    $depTime = \Carbon\Carbon::parse($itinerary['segments'][0]['segmentDeparture']['at']);
                                                    $depDate = $depTime->format('D, M d');
                                                    $depTimeFormatted = $depTime->format('H:i');
                                                @endphp
                                                <div class="font-bold text-sm text-brand-textDark">{{ $depDate }}</div>
                                                <div class="text-xs text-slate-600">{{ $depTimeFormatted }}</div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            @if(
                                                    isset($itinerary['segments'][count($itinerary['segments']) -
                                                        1]['segmentArrival']['at'])
                                                )
                                                @php
                                                    $arrTime = \Carbon\Carbon::parse($itinerary['segments'][count($itinerary['segments']) -
                                                        1]['segmentArrival']['at']);
                                                    $arrDate = $arrTime->format('D, M d');
                                                    $arrTimeFormatted = $arrTime->format('H:i');
                                                @endphp
                                                <div class="font-bold text-sm text-brand-textDark">{{ $arrDate }}</div>
                                                <div class="text-xs text-slate-500">{{ $arrTimeFormatted }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Baggage Summary -->
                            <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-100 mb-4">
                                <h4 class="text-[10px] font-bold text-slate-800 uppercase mb-3 tracking-wider">Baggage Allowance
                                </h4>
                                <div class="space-y-3">
                                    @php
                                        $bagInfo =
                                            $flightData['travelerPricings'][0]['fareDetailsBySegment'][0]['includedCheckedBags'] ??
                                            null;
                                        $displayBag = 'Included';
                                        if ($bagInfo) {
                                            if (isset($bagInfo['quantity'])) {
                                                $displayBag = $bagInfo['quantity'] . ' Bag(s) x 23kg';
                                            } else if (isset($bagInfo['weight'])) {
                                                $displayBag = '1 Bag x ' . $bagInfo['weight'] . ($bagInfo['weightUnit'] ?? 'kg');
                                            }
                                        }
                                    @endphp
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 text-slate-800">
                                            <i class="fas fa-suitcase-rolling text-brand-orange w-4"></i>
                                            <span>Checked Baggage</span>
                                        </div>
                                        <span class="font-bold text-brand-textDark">{{ $displayBag }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2 text-slate-800">
                                            <i class="fas fa-briefcase text-brand-blue w-4"></i>
                                            <span>Cabin Baggage</span>
                                        </div>
                                        <span class="font-bold text-brand-textDark">7kg Handbag</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Route Line -->
                        <div class="border-t border-dashed border-slate-200 my-4"></div>

                        <div class="mb-2">
                            <h4 class="font-bold text-md text-brand-textDark mb-3">Flight Base Fare</h4>
                            @if(isset($flightData['travelerPricings']))
                                @php
                                    // 1. Group the travelers by type (ADULT, CHILD, HELD_INFANT)
                                    $groupedDetails = collect($flightData['travelerPricings'])->groupBy('travelerType');
                                    $sumTot = 0;
                                    //dd($groupedDetails);
                                @endphp

                                @foreach($groupedDetails as $type => $group)
                                                @php
                                                    // 2. Calculate the count for this specific group
                                                    $count = $group->count();

                                                    // 3. Sum the price for all travelers in this group
                                                    $totalGroupPrice = $group->sum(function ($traveler) {
                                                        return $traveler['price']['base'];
                                                    });


                                                    // 4. Format the Label
                                                    $label = match ($type) {
                                                        'HELD_INFANT' => 'Infant',
                                                        'CHILD' => 'Child',
                                                        default => 'Adult'
                                                    };
                                                @endphp

                                                <div class="flex justify-between text-xs text-slate-800 mb-2">
                                                    {{-- Output: "Adult x (2)" --}}
                                                    <span>{{ $label }} x ({{ $count }})</span>

                                                    {{-- Output: Total price for that group --}}
                                                    <span class="font-medium">{{ config('app.currency_symbol') }}{{
                                    number_format($simlessPayService->convertNairaToPounds($totalGroupPrice)) }}</span>
                                                </div>
                                @endforeach
                            @endif
                            @if(isset($flightData['verifiedPriceBreakdown']['taxesAndFees']))
                                <div class="flex justify-between text-xs text-slate-800 mb-2">
                                    <span>Taxes and Fees</span>
                                    <span class="font-medium">{{ config('app.currency_symbol') }}
                                        {{number_format($simlessPayService->convertNairaToPounds($taxes)) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-xs text-slate-800 mb-2">
                                <span>Discount</span>
                                <span class="font-medium">{{ config('app.currency_symbol') }}0</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mt-6 pt-4 border-t border-slate-200">
                            <span class="text-sm font-bold text-slate-800">Total Price</span>
                            <span class="text-2xl font-bold text-brand-textDark">
                                @if(isset($flightData['verifiedPrice']['total']))
                                                        {{ config('app.currency_symbol') }}{{
                                    number_format($simlessPayService->convertNairaToPounds($total)) }}
                                @else
                                    {{ config('app.currency_symbol') }}0
                                @endif
                            </span>
                        </div>

                        <div class="mt-2 text-[10px] text-sky-600 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i> Prices are subject to change until booked
                        </div>
                    </div>

                    <!-- Help Box -->
                    <div class="bg-brand-lightBlue/30 p-5 border-t border-slate-100">
                        <h4 class="font-bold text-brand-blue text-sm mb-1">Need any help?</h4>
                        <p class="text-xs text-slate-500 mb-2">Our customer service teams are here to help you 24/7.</p>
                        <p class="text-xs text-slate-600 mb-1">Contact us on:</p>
                        <a href="tel:+2348096964423" class="text-sky-600 font-bold text-lg">+234 809 696 4423</a>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- BNPL Terms Modal -->
    <div id="bnpl-modal" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-6 border-b border-slate-200 bg-slate-50 rounded-t-xl">
                <h3 class="text-xl font-bold text-brand-blue">Credit Facility Terms & Conditions</h3>
                <button type="button" onclick="closeBnplModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 text-sm text-slate-600 space-y-4">
                <p>By proceeding with the "Buy Now, Pay Later" (BNPL) credit facility managed by Nurud, you agree to the following terms:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Immediate Reservation:</strong> Your selected flight will be booked and a PNR will be generated and reserved.</li>
                    <li><strong>Contact Requirement:</strong> You must contact our customer support team via phone within <strong>12 hours</strong> of this reservation to finalize your payment plan.</li>
                    <li><strong>Cancellation:</strong> Failure to contact us or reach an agreement within the stipulated 12 hours will lead to the automatic cancellation of your reserved ticket without prior notice.</li>
                    <li><strong>Alternative Payment:</strong> Should you decide against using the credit facility, you may settle the total amount via direct bank transfer to our provided accounts.</li>
                    <li><strong>Eligibility:</strong> The credit facility is subject to approval, and terms of the installment will be decided during your phone consultation.</li>
                </ul>
                <p>We look forward to helping you travel with flexibility!</p>
            </div>
            <div class="p-6 border-t border-slate-200 flex justify-end gap-3 bg-slate-50 rounded-b-xl">
                <button type="button" onclick="closeBnplModal()" class="px-6 py-2 rounded-full text-sm font-bold bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">Decline</button>
                <form method="POST" action="{{ route('bookings.store') }}" class="m-0">
                    @csrf
                    <input type="hidden" name="booking_type" value="pay_later">
                    <button type="submit" class="px-6 py-2 rounded-full text-sm font-bold bg-brand-blue text-white shadow-md hover:bg-blue-900 transition-colors">Accept & Kick Start Process</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function switchTab(tabName) {
            // Hide all contents
            ['hold', 'card', 'transfer', 'bnpl'].forEach(id => {
                document.getElementById('tab-' + id).classList.add('hidden');
                document.getElementById('tab-' + id).classList.remove('block');

                // Reset buttons to white
                const btn = document.getElementById('btn-' + id);
                btn.classList.remove('bg-brand-blue', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-brand-blue');
            });

            // Show selected content
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            document.getElementById('tab-' + tabName).classList.add('block');

            // Highlight selected button
            const activeBtn = document.getElementById('btn-' + tabName);
            activeBtn.classList.remove('bg-white', 'text-brand-blue');
            activeBtn.classList.add('bg-brand-blue', 'text-white', 'shadow-md');
        }

        // Initialize Mobile Menu
        const btn = document.getElementById('mobile-menu-btn');

        // BNPL Modal Scripts
        function openBnplModal() {
            document.getElementById('bnpl-modal').classList.remove('hidden');
        }
        function closeBnplModal() {
            document.getElementById('bnpl-modal').classList.add('hidden');
        }
    </script>
@endsection