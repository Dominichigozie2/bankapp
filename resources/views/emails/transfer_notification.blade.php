<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $details['subject'] }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f8f9fa; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#fff; border-radius:8px; padding:20px;">
        <h2 style="color:#004085;">{{ $details['subject'] }}</h2>
        <p>Hi {{ $details['user_name'] }},</p>

        <p>{{ $details['message'] }}</p>

        <p><strong>Amount:</strong> ${{ number_format($details['amount'], 2) }}</p>
        <p><strong>Transfer Type:</strong> {{ ucfirst($details['type']) }}</p>

        <p style="margin-top:20px;">Best regards,<br><strong>SpeedLight Bank Team</strong></p>
    </div>
</body>
</html>
