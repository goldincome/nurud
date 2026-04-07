<!DOCTYPE html>
<html>

<head>
    <title>Action Required: Complete Your Booking Payment</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #0b2c6a;">Hello {{ $booking->customer_first_name }} {{ $booking->customer_last_name }},</h2>

    <p>Thank you for choosing Nurud. Your flight has been reserved successfully and placed <strong>On Hold</strong>.</p>

    <div style="background-color: #f0fdf4; border-left: 4px solid #16a34a; padding: 15px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #15803d;">Immediate Action Required</h3>
        <p style="margin-bottom: 0;">Please complete your booking by making a full transfer to any of our bank accounts
            listed below within the next <strong>12 hours</strong>. <strong>If payment is not confirmed within 12 hours,
                this temporary reservation will automatically be cancelled.</strong></p>
        <p style="margin-top: 10px; margin-bottom: 0;">If you have any issues or require assistance, please contact us
            immediately on phone at <strong>+44 (0) 2032474747</strong>.</p>
    </div>

    <h3>Temporary Booking Details</h3>
    <ul>
        <li><strong>Booking Reference:</strong> {{ $booking->reference_number }}</li>
        <li><strong>Total Passengers:</strong> {{ $booking->travelers()->count() ?? 1 }}</li>
        <li><strong>Total Base Price:</strong> {{ number_format($booking->base_price, 2) }} {{ $booking->currency }}
        </li>
        <li><strong>Taxes & Fees:</strong> {{ number_format($booking->taxes_and_fees, 2) }} {{ $booking->currency }}
        </li>
        <li><strong>Total Flight Amount:</strong> <strong>{{ number_format($booking->total_price, 2) }}
                {{ $booking->currency }}</strong></li>
    </ul>

    <h3>Account Details for Payment</h3>
    <p>Please transfer exactly <strong>{{ number_format($booking->total_price, 2) }} {{ $booking->currency }}</strong>
        to any of the following accounts:</p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
            <tr style="background-color: #f1f5f9;">
                <th style="text-align: left;">Bank Name</th>
                <th style="text-align: left;">IBAN/Account Number</th>
                <th style="text-align: left;">Account Name</th>
                <th style="text-align: left;">Additional Info</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banks as $bank)
                <tr>
                    <td>{{ $bank->bank_name }}</td>
                    <td><strong style="font-size: 1.1em;">{{ $bank->iban ?: $bank->account_number }}</strong></td>
                    <td>{{ $bank->account_name }}</td>
                    <td style="font-size: 0.9em; color: #555;">
                        @if($bank->swift_code)
                        <div><strong>SWIFT:</strong> {{ $bank->swift_code }}</div>@endif
                        @if($bank->routing_number)
                        <div><strong>Routing:</strong> {{ $bank->routing_number }}</div>@endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 30px;">Once you've made the transfer, please reply to this email or send your payment receipt
        to us so we can issue your ticket promptly.</p>

    <p>Thank you for flying with us!</p>
    <p>Best Regards,<br><strong>Nurud Customer Support</strong></p>
</body>

</html>