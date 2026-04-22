<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payment Processing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #002D72;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }

        h1 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Payment Processing</h1>
    </div>
    <div class="content">
        <p>Dear Customer,</p>
        <p>Your payment for reservation reference <strong>{{ $booking->reference_number }}</strong> is currently
            processing.</p>
        <p>Please note that this is a temporary notification. You will receive another email shortly to confirm whether
            the payment was successful or if it was declined.</p>
        <p>If you have any questions, please contact our support team.</p>
        <p>Thank you for choosing us.</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Nurud Travel. All rights reserved.
    </div>
</body>

</html>