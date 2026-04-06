<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Reservation - {{ $booking->reservation_id }}</title>

    {{-- CDN: TailwindCSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- CDN: Google Fonts (Inter) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- CDN: Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        @page {
            margin: 30px 40px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }

        /* ── Header ── */
        .header-table {
            width: 100%;
            margin-bottom: 5px;
        }

        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: #002D72;
        }

        .logo-text span {
            color: #F58220;
        }

        .header-title {
            color: #002D72;
            font-size: 18px;
            font-weight: bold;
            margin-top: 8px;
        }

        .greeting {
            font-size: 10px;
            color: #555;
            margin-top: 6px;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        /* ── Reservation ID Box ── */
        .reservation-box {
            text-align: center;
            padding: 10px 0;
            margin-bottom: 15px;
            border-top: 2px dashed #F58220;
            border-bottom: 2px dashed #F58220;
        }

        .reservation-label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .reservation-id {
            font-size: 16px;
            font-weight: bold;
            color: #002D72;
            letter-spacing: 1px;
        }

        /* ── Section Headers ── */
        .section-label {
            font-size: 11px;
            font-weight: bold;
            color: #002D72;
            margin-top: 25px;
            margin-bottom: 8px;
            padding-bottom: 4px;
        }

        .dashed-line {
            border-top: 2px dashed #F58220;
            margin: 15px 0;
            opacity: 0.6;
        }

        /* ── Flight Segment ── */
        .segment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .segment-table td {
            vertical-align: top;
            padding: 8px 5px;
        }

        .seg-time {
            font-size: 13px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .seg-date {
            font-size: 9px;
            color: #64748b;
        }

        .seg-airport-code {
            font-size: 11px;
            font-weight: bold;
            color: #002D72;
        }

        .seg-airport-name {
            font-size: 8px;
            color: #888;
            line-height: 1.3;
        }

        .seg-middle {
            text-align: center;
            vertical-align: middle;
        }

        .seg-duration {
            font-size: 9px;
            color: #555;
            font-weight: bold;
        }

        .seg-cabin {
            font-size: 8px;
            color: #002D72;
            text-transform: uppercase;
            font-weight: bold;
        }

        .seg-dotted-line {
            border-top: 1px dashed #ccc;
            margin: 4px auto;
            width: 90%;
        }

        .seg-right {
            text-align: right;
        }

        .airline-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .airline-row td {
            padding: 3px 5px;
            font-size: 9px;
            color: #64748b;
        }

        /* ── Layover ── */
        .layover-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 5px 10px;
            font-size: 9px;
            color: #92400e;
            margin-bottom: 5px;
        }

        /* ── Travelers Table ── */
        .travelers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .travelers-table th {
            text-align: left;
            padding: 7px 10px;
            color: #002D72;
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
        }

        .travelers-table td {
            padding: 7px 10px;
            font-size: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* ── Contact Info ── */
        .contact-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .contact-table td {
            padding: 5px 10px;
            font-size: 10px;
        }

        .contact-label {
            color: #888;
            font-weight: bold;
            width: 40%;
        }

        .contact-value {
            color: #333;
        }

        /* ── Price Table ── */
        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .price-table th {
            text-align: left;
            padding: 6px 10px;
            color: #002D72;
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
        }

        .price-table th:last-child {
            text-align: right;
        }

        .price-table td {
            padding: 5px 10px;
            font-size: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .price-table td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .price-table .total-row td {
            font-size: 12px;
            font-weight: bold;
            color: #002D72;
            border-top: 2px solid #002D72;
            border-bottom: none;
            padding-top: 8px;
        }

        /* ── Bank Details ── */
        .bank-section {
            margin-top: 20px;
            padding: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .bank-title {
            font-size: 11px;
            font-weight: bold;
            color: #002D72;
            margin-bottom: 8px;
        }

        .bank-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bank-table th {
            text-align: left;
            padding: 5px 8px;
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }

        .bank-table td {
            padding: 5px 8px;
            font-size: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
        }

        .footer-contact {
            font-size: 9px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .page-break {
            page-break-before: always;
        }

        /* ── Airline Logo ── */
        .airline-logo {
            width: 28px;
            height: 28px;
            object-fit: contain;
            vertical-align: middle;
            margin-right: 6px;
            border-radius: 4px;
        }

        .airline-logo-placeholder {
            width: 28px;
            height: 28px;
            display: inline-block;
            background: #e2e8f0;
            border-radius: 4px;
            text-align: center;
            line-height: 28px;
            font-size: 12px;
            color: #002D72;
            font-weight: bold;
            vertical-align: middle;
            margin-right: 6px;
        }
    </style>
</head>

<body>
    {{-- ───────────── HEADER ───────────── --}}
    <table class="header-table">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="logo-text">Nurud<span>Travel.</span></div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: top;">
                <img src="https://pics.avs.io/200/60/{{ $booking->carrier_code }}.png" alt="{{ $booking->carrier_code }}" style="height: 30px; object-fit: contain;" onerror="this.style.display='none'">
            </td>
        </tr>
    </table>

    <div class="header-title">Flight Reservation Details</div>
    <div class="greeting">
        Dear Esteemed Customer, We are pleased to confirm your
        flight reservation. <em>Your reservation is <strong>{{ strtoupper(str_replace('_', ' ', $booking->status->value)) }}</strong>.</em>
        @if($booking->status->value === 'pending_payment' && $booking->expires_at)
            <br>Please complete payment before <strong>{{ $booking->expires_at->format('D, M d, Y - H:i') }}</strong> to avoid cancellation.
        @endif
    </div>

    {{-- ───────────── REFERENCE NUMBER & RESERVATION ID ───────────── --}}
    <div class="reservation-box">
        <div class="reservation-label">Booking Reference</div>
        <div class="reservation-id" style="text-decoration: underline; text-decoration-color: #F58220;">{{ $booking->reference_number }}</div>
        <div style="font-size: 9px; color: #888; margin-top: 4px;">PNR: {{ $booking->reservation_id }}</div>
    </div>

    <div class="dashed-line"></div>

    {{-- ───────────── FLIGHT ITINERARY ───────────── --}}
    @foreach($booking->itineraries->sortBy('itinerary_index') as $itinerary)
        @php
            $segments = $itinerary->segments ?? [];
            $firstSeg = $segments[0] ?? null;
            $lastSeg = $segments[count($segments) - 1] ?? null;
            $isReturn = $itinerary->itinerary_index >= 2;
        @endphp

        <div class="section-label">
            {{ $isReturn ? 'Returning' : 'Departing' }} Flight
            @if($firstSeg)
                ({{ \Carbon\Carbon::parse($firstSeg['segmentDeparture']['at'])->format('D, M d, Y') }})
            @endif
        </div>
        <div style="font-size: 9px; color: #888; margin-bottom: 8px;">
            {{ $firstSeg['segmentDeparture']['airport']['city'] ?? '' }} to {{ $lastSeg['segmentArrival']['airport']['city'] ?? '' }}
        </div>

        @foreach($segments as $segIndex => $segment)
            {{-- Layover --}}
            @if($segIndex > 0 && !empty($segment['layOverDuration']))
                <div class="layover-box">
                    ⏳ <strong>Layover at {{ $segment['segmentDeparture']['airport']['name'] ?? '' }}
                        ({{ $segment['segmentDeparture']['airport']['iataCode'] ?? '' }})</strong>
                    &mdash; Waiting time: {{ $segment['layOverDuration'] }}
                </div>
            @endif

            <table class="segment-table">
                <tr>
                    {{-- DEPARTURE --}}
                    <td style="width: 35%;">
                        <div class="seg-time">{{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('H:i') : '--:--' }}</div>
                        <div class="seg-airport-code">{{ $segment['segmentDeparture']['airport']['iataCode'] ?? '' }}
                            ({{ $segment['segmentDeparture']['airport']['city'] ?? '' }})</div>
                        <div class="seg-date">{{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('M d, Y') : '' }}</div>
                        <div class="seg-airport-name">{{ $segment['segmentDeparture']['airport']['name'] ?? '' }}</div>
                    </td>
                    {{-- MIDDLE --}}
                    <td class="seg-middle" style="width: 30%;">
                        <div class="seg-cabin">{{ $booking->cabin ?? 'Economy' }}</div>
                        <div class="seg-dotted-line"></div>
                        <div class="seg-duration">Duration: {{ $segment['duration'] ?? '' }}</div>
                    </td>
                    {{-- ARRIVAL with airline logo --}}
                    <td class="seg-right" style="width: 35%;">
                        @if(isset($segment['carrier']['iataCode']))
                            <div style="margin-bottom: 4px;">
                                <img src="https://pics.avs.io/80/80/{{ $segment['carrier']['iataCode'] }}.png"
                                     class="airline-logo"
                                     alt="{{ $segment['carrier']['name'] ?? '' }}"
                                     onerror="this.outerHTML='<span class=airline-logo-placeholder>{{ $segment['carrier']['iataCode'] }}</span>'">
                            </div>
                        @endif
                        <div class="seg-time">{{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('H:i') : '--:--' }}</div>
                        <div class="seg-airport-code">{{ $segment['segmentArrival']['airport']['iataCode'] ?? '' }}
                            ({{ $segment['segmentArrival']['airport']['city'] ?? '' }})</div>
                        <div class="seg-date">{{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('M d, Y') : '' }}</div>
                        <div class="seg-airport-name">{{ $segment['segmentArrival']['airport']['name'] ?? '' }}</div>
                    </td>
                </tr>
            </table>

            {{-- Airline info row --}}
            <table class="airline-row">
                <tr>
                    <td>
                        @if(isset($segment['carrier']['iataCode']))
                            <img src="https://pics.avs.io/100/30/{{ $segment['carrier']['iataCode'] }}.png"
                                 style="height: 14px; object-fit: contain; vertical-align: middle; margin-right: 5px;"
                                 alt="{{ $segment['carrier']['name'] ?? '' }}"
                                 onerror="this.style.display='none'">
                        @endif
                        {{ $segment['carrier']['name'] ?? '' }}
                        ({{ $segment['carrier']['iataCode'] ?? '' }}{{ $segment['number'] ?? '' }})
                        &bull; Aircraft: {{ $segment['aircraft']['code'] ?? 'N/A' }}
                        @php
                            $bagInfo = $booking->travelerPricings[0]->fare_details_by_segment[$segIndex]['includedCheckedBags'] ?? null;
                            $bagDisplay = 'Included';
                            if ($bagInfo) {
                                if (isset($bagInfo['quantity']) && $bagInfo['quantity'] > 0) {
                                    $bagDisplay = $bagInfo['quantity'] . 'PCS 23kg';
                                } elseif (isset($bagInfo['weight']) && $bagInfo['weight'] > 0) {
                                    $bagDisplay = '1PC x ' . $bagInfo['weight'] . ($bagInfo['weightUnit'] ?? 'kg');
                                }
                            }
                        @endphp
                        &bull; Baggage: {{ $bagDisplay }}
                    </td>
                </tr>
            </table>
        @endforeach
    @endforeach

    <div class="dashed-line"></div>

    {{-- ───────────── TRAVELERS ───────────── --}}
    <div class="section-label">Travelers</div>
    <table class="travelers-table">
        <thead>
            <tr>
                <th style="width: 70%;">Traveler's Name</th>
                <th style="width: 30%;">Traveler's Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->travelers as $index => $traveler)
                <tr>
                    <td>{{ strtoupper($traveler->gender?->label() ?? '') }}. {{ strtoupper($traveler->first_name) }} {{ strtoupper($traveler->last_name) }}</td>
                    <td>{{ ucfirst(\App\Enums\TravelerType::from($booking->travelerPricings[$index]->traveler_type)->label() ?? 'Adult') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed-line"></div>

    {{-- ───────────── CONTACT INFORMATION ───────────── --}}
    <div class="section-label">Contact Information</div>
    <table class="contact-table">
        <tr>
            <td class="contact-label">Phone Number</td>
            <td class="contact-value">{{ $booking->contact_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="contact-label">Email Address</td>
            <td class="contact-value">{{ $booking->customer_email }}</td>
        </tr>
    </table>

    <div class="dashed-line"></div>

    {{-- ───────────── PRICE DETAILS ───────────── --}}
    <div class="section-label">Price Details</div>
    <table class="price-table">
        <thead>
            <tr>
                <th>Traveler(s)</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $groupedPricings = $booking->travelerPricings->groupBy('traveler_type');
            @endphp
            @foreach($groupedPricings as $type => $group)
                @php
                    $count = $group->count();
                    $totalGroupPrice = $group->sum(fn($p) => $p->price['total'] ?? $p->price['base'] ?? 0);
                    $label = \App\Enums\TravelerType::from($type)->label();
                @endphp
                <tr>
                    <td>{{ $label }} (x{{ $count }})</td>
                    <td>{{ $booking->currency }} {{ number_format($totalGroupPrice) }}</td>
                </tr>
            @endforeach
            <tr>
                <td>Taxes & Fees</td>
                <td>{{ $booking->currency }} {{ number_format($booking->taxes_and_fees + $booking->markup_fee) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total</td>
                <td>{{ $booking->currency }} {{ number_format($booking->total_price + $booking->markup_fee) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- ───────────── BANK DETAILS (only for pending) ───────────── --}}
    @if($booking->status->value !== 'confirmed')
        <div class="bank-section">
            <div class="bank-title">Bank Details for Payment</div>
            <div style="font-size: 9px; color: #64748b; margin-bottom: 8px;">
                Please use <strong>{{ $booking->reference_number }}</strong> as payment reference.
            </div>
            <table class="bank-table">
                <thead>
                    <tr>
                        <th>Bank Details</th>
                        <th>Account Number</th>
                        <th>Account Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banks as $bank)
                        <tr>
                            <td>
                                <strong>{{ $bank->bank_name }}</strong><br>
                                @if($bank->swift_code)<span style="font-size: 8px; color: #64748b;">SWIFT: {{ $bank->swift_code }}</span><br>@endif
                                @if($bank->iban)<span style="font-size: 8px; color: #64748b;">IBAN: {{ $bank->iban }}</span><br>@endif
                                @if($bank->routing_number)<span style="font-size: 8px; color: #64748b;">Routing: {{ $bank->routing_number }}</span>@endif
                            </td>
                            <td>{{ $bank->account_number }}</td>
                            <td>{{ $bank->account_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center;">No bank accounts available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- ───────────── FOOTER ───────────── --}}
    <div class="footer">
        <div class="footer-contact">
            Phone: +234 809 696 4423 &bull;
            Email: support@nurud.com &bull;
            Web: www.nurud.com
        </div>
        &copy; {{ date('Y') }} Nurud Travel. All rights reserved. This is an electronically generated document.
    </div>
</body>

</html>
