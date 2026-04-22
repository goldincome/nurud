<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #000; line-height: 1.6; background: #f4f4f7; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #002D72; color: #fff; padding: 20px 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .body { padding: 25px 30px; }
        .label { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-top: 12px; }
        .value { font-size: 14px; color: #000; font-weight: 600; }
        .highlight { background: #e0f2fe; border-left: 4px solid #002D72; padding: 12px 16px; margin: 16px 0; border-radius: 4px; }
        .footer { background: #f8fafc; padding: 15px 30px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🆕 New Reservation Created</h1>
        </div>
        <div class="body">
            <p>A new flight reservation has been made on Nurud Travel.</p>

            <div class="highlight">
                <div class="label">Booking Reference</div>
                <div class="value" style="font-size: 18px;">{{ $booking->reference_number }}</div>
            </div>

            <div class="label">Customer Name</div>
            <div class="value">{{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</div>

            <div class="label">Customer Email</div>
            <div class="value">{{ $booking->customer_email }}</div>

            <div class="label">Route</div>
            <div class="value">{{ $booking->origin_location }} → {{ $booking->origin_destination }}</div>

            <div class="label">Departure Date</div>
            <div class="value">{{ $booking->departure_date ? $booking->departure_date->format('D, M d, Y') : 'N/A' }}</div>

            <div class="label">Total Price</div>
            <div class="value">{{ $booking->currency }} {{ number_format($booking->total_price) }}</div>

            <div class="label">Status</div>
            <div class="value">{{ ucwords(str_replace('_', ' ', $booking->status->value)) }}</div>

            <div class="label">Created At</div>
            <div class="value">{{ $booking->created_at->format('M d, Y H:i:s') }}</div>

            <p style="margin-top: 20px;">
                <a href="{{ route('admin.bookings.show', $booking->id) }}" style="display: inline-block; padding: 10px 24px; background: #002D72; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;">View Booking in Admin</a>
            </p>
        </div>
        <div class="footer">
            This is an automated notification from Nurud Travel Admin System.
        </div>
    </div>
</body>
</html>
