<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subjectLine }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #002D72;">Nurud Travels</h2>
        </div>
        <div style="color: #333; line-height: 1.6;">
            {!! nl2br(e($messageBody)) !!}
        </div>
        <div style="margin-top: 40px; font-size: 12px; color: #888; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
            You received this email because you are subscribed to Nurud Travels Alerts.
        </div>
    </div>
</body>
</html>
