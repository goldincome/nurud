<!DOCTYPE html>
<html>

<head>
    <title>Action Required: Finalize Your Booking</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2 style="color: #0b2c6a;">Hello {{ $booking->customer_first_name }} {{ $booking->customer_last_name }},</h2>

    <p>Thank you for choosing Nurud. Your flight choice has been reserved successfully utilizing our <strong>Buy Now,
            Pay Later</strong> (Credit Facility) workflow.</p>

    <div style="background-color: #fff3e0; border-left: 4px solid #f97316; padding: 15px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #c2410c;">Urgent Action Required</h3>
        <p style="margin-bottom: 0;">Please contact us via phone immediately at <strong>+44 (0) 2032474747</strong> to
            finalize your flexible payment and credit arrangement. <strong>If we do not communicate within 12 hours of
                this email, this reservation will automatically be cancelled.</strong>
            You will be asked to provide your reference number below.</p>
    </div>

    <h3>Booking Details</h3>
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

    <h3>Alternative: Direct Payment</h3>
    <p>If you prefer to pay the full amount without utilizing our credit facility, you may transfer the funds directly
        to any of our bank accounts listed below:</p>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
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

    <p style="margin-top: 30px;">Thank you for flying with us!</p>
    <p>Best Regards,<br><strong>Nurud Customer Support</strong></p>
</body>

</html>