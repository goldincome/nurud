<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #000; line-height: 1.6; background: #f4f4f7; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #dc2626; color: #fff; padding: 20px 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .body { padding: 25px 30px; }
        .label { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-top: 12px; }
        .value { font-size: 14px; color: #000; font-weight: 600; }
        .alert-box { background: #fef2f2; border-left: 4px solid #dc2626; padding: 12px 16px; margin: 16px 0; border-radius: 4px; }
        .error-box { background: #fff7ed; border: 1px solid #fed7aa; padding: 12px; margin-top: 16px; border-radius: 4px; font-family: monospace; font-size: 12px; color: #9a3412; word-break: break-all; }
        .footer { background: #f8fafc; padding: 15px 30px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 URGENT: Payment Confirmed — Ticket NOT Issued</h1>
        </div>
        <div class="body">
            <div class="alert-box">
                <strong>Action Required:</strong> A customer's Stripe payment has been confirmed but the ticket could NOT be issued. Manual intervention is needed immediately.
            </div>

            <div class="label">Booking Reference</div>
            <div class="value" style="font-size: 18px;">{{ $booking->reference_number }}</div>

            <div class="label">Reservation ID</div>
            <div class="value">{{ $booking->reservation_id }}</div>

            <div class="label">Customer Name</div>
            <div class="value">{{ $booking->customer_first_name }} {{ $booking->customer_last_name }}</div>

            <div class="label">Customer Email</div>
            <div class="value">{{ $booking->customer_email }}</div>

            <div class="label">Route</div>
            <div class="value">{{ $booking->origin_location }} → {{ $booking->origin_destination }}</div>

            <div class="label">Total Price</div>
            <div class="value">{{ $booking->currency }} {{ number_format($booking->total_price) }}</div>

            @if(!empty($error))
            <div class="label">Error Details</div>
            <div class="error-box">{{ $error }}</div>
            @endif

            <p style="margin-top: 20px;">
                <a href="{{ route('admin.bookings.show', $booking->id) }}" style="display: inline-block; padding: 10px 24px; background: #dc2626; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold;">View Booking in Admin</a>
            </p>

            <p style="margin-top: 16px; font-size: 13px; color: #64748b;">
                <strong>Suggested Actions:</strong><br>
                1. Check the 247 Travels API status<br>
                2. Attempt to manually issue the ticket<br>
                3. Contact the customer if needed<br>
                4. Consider issuing a refund if ticket cannot be issued
            </p>
        </div>
        <div class="footer">
            This is an automated URGENT notification from Nurud Travel Admin System.
        </div>
    </div>
</body>
</html>
