<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #000; line-height: 1.6; background: #f4f4f7; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: #b91c1c; color: #fff; padding: 20px 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .body { padding: 25px 30px; }
        .label { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-top: 12px; }
        .value { font-size: 14px; color: #000; font-weight: 600; }
        .alert-box { background: #fef2f2; border-left: 4px solid #b91c1c; padding: 12px 16px; margin: 16px 0; border-radius: 4px; }
        .error-box { background: #fff7ed; border: 1px solid #fed7aa; padding: 12px; margin-top: 16px; border-radius: 4px; font-family: monospace; font-size: 12px; color: #9a3412; word-break: break-all; }
        .footer { background: #f8fafc; padding: 15px 30px; text-align: center; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ 247 Travels API is DOWN</h1>
        </div>
        <div class="body">
            <div class="alert-box">
                <strong>Service Disruption:</strong> The 247 Travels flight API is currently unreachable. Flight searches, reservations, and ticket issuance may be affected.
            </div>

            <div class="label">Detected At</div>
            <div class="value">{{ now()->format('M d, Y H:i:s') }} (Server Time)</div>

            @if(!empty($endpoint))
            <div class="label">Endpoint</div>
            <div class="value">{{ $endpoint }}</div>
            @endif

            @if(!empty($error))
            <div class="label">Error Details</div>
            <div class="error-box">{{ $error }}</div>
            @endif

            <p style="margin-top: 20px; font-size: 13px; color: #64748b;">
                <strong>Suggested Actions:</strong><br>
                1. Check the 247 Travels API status page<br>
                2. Verify network connectivity from the server<br>
                3. Contact 247 Travels support if the issue persists<br>
                4. Monitor incoming bookings for failures
            </p>

            <p style="margin-top: 12px; font-size: 12px; color: #94a3b8;">
                <em>Note: This notification is throttled to once every 15 minutes to prevent inbox flooding.</em>
            </p>
        </div>
        <div class="footer">
            This is an automated notification from Nurud Travel Admin System.
        </div>
    </div>
</body>
</html>
