<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Flight Ticket - {{ $booking->reservation_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .ticket-container {
            max-width: 700px;
            margin: 20px auto;
            border: 2px solid #002D72;
            border-radius: 12px;
            overflow: hidden;
        }

        .ticket-header {
            background: #002D72;
            color: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-header .logo {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .ticket-header .logo span {
            color: #F58220;
        }

        .ticket-header .ref {
            font-size: 11px;
            text-align: right;
            opacity: 0.85;
        }

        .flight-banner {
            background: linear-gradient(135deg, #002D72, #1a4a8f);
            color: #fff;
            padding: 25px 30px;
        }

        .route-row {
            display: table;
            width: 100%;
        }

        .route-cell {
            display: table-cell;
            vertical-align: middle;
        }

        .route-cell.origin,
        .route-cell.dest {
            width: 30%;
        }

        .route-cell.middle {
            width: 40%;
            text-align: center;
        }

        .city-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .city-label {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.7;
            margin-top: 2px;
        }

        .route-cell.dest {
            text-align: right;
        }

        .plane-icon {
            font-size: 16px;
            opacity: 0.6;
        }

        .flight-line {
            border-top: 1px dashed rgba(255, 255, 255, 0.3);
            margin: 4px 0;
        }

        .carrier-info {
            font-size: 10px;
            opacity: 0.7;
            margin-top: 4px;
        }

        .details-section {
            padding: 20px 30px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #002D72;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #F58220;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .info-item {
            display: table-cell;
            width: 33.33%;
            padding: 6px 0;
            vertical-align: top;
        }

        .info-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #999;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 12px;
            color: #333;
            font-weight: 600;
            margin-top: 2px;
        }

        .travelers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .travelers-table th {
            background: #f1f5f9;
            text-align: left;
            padding: 8px 12px;
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .travelers-table td {
            padding: 8px 12px;
            font-size: 11px;
            border-bottom: 1px solid #e2e8f0;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-pending {
            background: #fef9c3;
            color: #ca8a04;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .ticket-footer {
            background: #f8fafc;
            padding: 15px 30px;
            border-top: 1px dashed #cbd5e1;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }

        .divider {
            border-top: 1px dashed #cbd5e1;
            margin: 0;
        }

        .price-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 20px;
            text-align: center;
            margin-top: 15px;
        }

        .price-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
        }

        .price-value {
            font-size: 22px;
            font-weight: bold;
            color: #002D72;
        }

        .itinerary-header {
            background: #f1f5f9;
            padding: 10px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .itinerary-title {
            font-size: 13px;
            font-weight: bold;
            color: #002D72;
        }

        .itinerary-summary {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        .itinerary-duration {
            font-size: 10px;
            color: #002D72;
            font-weight: bold;
        }

        .segment-row {
            display: table;
            width: 100%;
            padding: 12px 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .segment-cell {
            display: table-cell;
            vertical-align: top;
        }

        .segment-cell.dep {
            width: 35%;
        }

        .segment-cell.mid {
            width: 30%;
            text-align: center;
            vertical-align: middle;
        }

        .segment-cell.arr {
            width: 35%;
            text-align: right;
        }

        .seg-time {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
        }

        .seg-date {
            font-size: 9px;
            color: #64748b;
        }

        .seg-code {
            font-size: 13px;
            font-weight: bold;
            color: #002D72;
            margin-top: 3px;
        }

        .seg-airport {
            font-size: 9px;
            color: #64748b;
        }

        .seg-city {
            font-size: 8px;
            color: #94a3b8;
        }

        .seg-duration {
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }

        .seg-carrier {
            font-size: 9px;
            color: #64748b;
            margin-top: 4px;
        }

        .seg-flight-no {
            font-size: 9px;
            color: #94a3b8;
        }

        .seg-line {
            border-top: 1px dashed #cbd5e1;
            margin: 4px auto;
            width: 80%;
        }

        .layover-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 6px 20px;
            font-size: 10px;
        }

        .layover-label {
            font-weight: bold;
            color: #92400e;
        }

        .layover-detail {
            color: #a16207;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <div class="logo">Nurud <span>Travel</span></div>
            <div class="ref">
                Ref: <strong>{{ $booking->reference_number }}</strong><br>
                @if($booking->status->value === 'confirmed')
                PNR: {{ $booking->reservation_id }}<br>
                @endif
                Date: {{ $booking->created_at->format('M d, Y') }}
            </div>
        </div>

        <!-- Flight Route Banner -->
        <div class="flight-banner">
            <div class="route-row">
                <div class="route-cell origin">
                    <div class="city-code">{{ $booking->origin_location }}</div>
                    <div class="city-label">Departure</div>
                </div>
                <div class="route-cell middle">
                    <div class="flight-line"></div>
                    <div class="plane-icon">✈</div>
                    <div class="flight-line"></div>
                    <div class="carrier-info">{{ $booking->carrier_code }} &bull; {{ $booking->class ?? 'Economy' }}
                    </div>
                </div>
                <div class="route-cell dest">
                    <div class="city-code">{{ $booking->origin_destination }}</div>
                    <div class="city-label">Arrival</div>
                </div>
            </div>
        </div>

        <!-- Flight Details from Itineraries -->
        @forelse($booking->itineraries->sortBy('itinerary_index') as $itinerary)
            <div style="border-bottom: 1px dashed #cbd5e1;">
                <div class="itinerary-header">
                    <div style="display: table; width: 100%;">
                        <div style="display: table-cell; vertical-align: middle;">
                            <div class="itinerary-title">{{ $itinerary->itinerary_index === 2 ? '↩' : '✈' }} {{ $itinerary->itinerary_title ?? 'Flight ' . $itinerary->itinerary_index }}</div>
                            <div class="itinerary-summary">{{ $itinerary->itinerary_summary }}</div>
                        </div>
                        <div style="display: table-cell; vertical-align: middle; text-align: right;">
                            <div class="itinerary-duration">Duration: {{ $itinerary->duration }}</div>
                            <div style="font-size: 9px; color: #64748b;">
                                @if(count($itinerary->segments ?? []) > 1)
                                    {{ count($itinerary->segments) - 1 }} stop(s)
                                @else
                                    Direct
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($itinerary->segments ?? [] as $segIndex => $segment)
                    @if($segIndex > 0 && !empty($segment['layOverDuration']))
                        <div class="layover-box">
                            <span class="layover-label">⏳ Layover in {{ $segment['segmentDeparture']['airport']['city'] ?? '' }}</span>
                            <span class="layover-detail">&mdash; {{ $segment['layOverDuration'] }} at {{ $segment['segmentDeparture']['airport']['name'] ?? '' }} ({{ $segment['segmentDeparture']['airport']['iataCode'] ?? '' }})</span>
                        </div>
                    @endif

                    <div class="segment-row">
                        <div class="segment-cell dep">
                            <div class="seg-time">{{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('H:i') : '--:--' }}</div>
                            <div class="seg-date">{{ isset($segment['segmentDeparture']['at']) ? \Carbon\Carbon::parse($segment['segmentDeparture']['at'])->format('D, M d, Y') : '' }}</div>
                            <div class="seg-code">{{ $segment['segmentDeparture']['airport']['iataCode'] ?? '' }}</div>
                            <div class="seg-airport">{{ $segment['segmentDeparture']['airport']['name'] ?? '' }}</div>
                            <div class="seg-city">{{ $segment['segmentDeparture']['airport']['city'] ?? '' }}, {{ $segment['segmentDeparture']['airport']['country'] ?? '' }}</div>
                        </div>
                        <div class="segment-cell mid">
                            <div class="seg-duration">{{ $segment['duration'] ?? '' }}</div>
                            <div class="seg-line"></div>
                            <div style="font-size: 12px; color: #64748b;">✈</div>
                            <div class="seg-line"></div>
                            <div class="seg-carrier">{{ $segment['carrier']['name'] ?? '' }}</div>
                            <div class="seg-flight-no">{{ $segment['carrier']['iataCode'] ?? '' }}{{ $segment['number'] ?? '' }} &bull; Aircraft {{ $segment['aircraft']['code'] ?? 'N/A' }}</div>
                        </div>
                        <div class="segment-cell arr">
                            <div class="seg-time">{{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('H:i') : '--:--' }}</div>
                            <div class="seg-date">{{ isset($segment['segmentArrival']['at']) ? \Carbon\Carbon::parse($segment['segmentArrival']['at'])->format('D, M d, Y') : '' }}</div>
                            <div class="seg-code">{{ $segment['segmentArrival']['airport']['iataCode'] ?? '' }}</div>
                            <div class="seg-airport">{{ $segment['segmentArrival']['airport']['name'] ?? '' }}</div>
                            <div class="seg-city">{{ $segment['segmentArrival']['airport']['city'] ?? '' }}, {{ $segment['segmentArrival']['airport']['country'] ?? '' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <!-- Fallback if no itineraries -->
            <div class="details-section">
                <div class="section-title">Flight Details</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Route</div>
                        <div class="info-value">{{ $booking->origin_location }} → {{ $booking->origin_destination }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Departure Date</div>
                        <div class="info-value">{{ $booking->departure_date ? $booking->departure_date->format('M d, Y H:i') : 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Carrier</div>
                        <div class="info-value">{{ $booking->carrier_code }} &bull; {{ $booking->class ?? 'Economy' }}</div>
                    </div>
                </div>
            </div>
        @endforelse

        <!-- Booking Status -->
        <div class="details-section">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">{{ ucwords(str_replace('_', ' ', $booking->payment_method->value ?? $booking->payment_method ?? 'N/A')) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge
                            @if($booking->status->value === 'confirmed') status-confirmed
                            @elseif($booking->status->value === 'cancelled') status-cancelled
                            @else status-pending @endif">
                            {{ ucwords(str_replace('_', ' ', $booking->status->value)) }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Cabin Class</div>
                    <div class="info-value">{{ $booking->class ?? 'Economy' }}</div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Travelers -->
        <div class="details-section">
            <div class="section-title">Passenger Information</div>
            <table class="travelers-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->travelers as $index => $traveler)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $traveler->first_name }} {{ $traveler->last_name }}</strong></td>
                            <td>{{ ucfirst(\App\Enums\TravelerType::from($booking->travelerPricings[$index]->traveler_type)->label() ?? 'Adult') }}
                            </td>
                            <td>{{ ucfirst($traveler->gender->label() ?? 'N/A') }}</td>
                            <td>{{ $traveler->date_of_birth ? \Carbon\Carbon::parse($traveler->date_of_birth)->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="divider"></div>

        <!-- Contact & Pricing -->
        <div class="details-section">
            <div class="section-title">Contact &amp; Pricing</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Contact Name</div>
                    <div class="info-value">{{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $booking->customer_email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">{{ $booking->contact_phone ?? 'N/A' }}</div>
                </div>
            </div>

            <div class="price-box">
                <div class="price-label">Total Amount</div>
                <div class="price-value">{{ $booking->currency }} {{ number_format($booking->total_price) }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            This is an electronic ticket. Please present this document at check-in. &bull; Nurud Travel &copy;
            {{ date('Y') }}
        </div>
    </div>
</body>

</html>