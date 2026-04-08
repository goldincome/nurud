<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Declined</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #d9534f; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; border: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
        h1 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Declined</h1>
    </div>
    <div class="content">
        <p>Dear Customer,</p>
        <p>We're writing to inform you that your payment for reservation reference <strong>{{ $booking->reference_number }}</strong> was declined.</p>
        <p>Please follow up to resolve this issue. Your reservation will be cancelled within 12 hours if payment is not successfully completed.</p>
        <p>You can try again using a different payment method, or contact our support line or email for assistance.</p>
        <p>Thank you for your prompt attention to this matter.</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Nurud Travel. All rights reserved.
    </div>
</body>
</html>
