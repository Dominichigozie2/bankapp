<!-- resources/views/emails/registration.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to SpeedLight Bank</title>
</head>
<body>
    <h2>🎉 Welcome, {{ $user->first_name }}!</h2>

    <p>We’re excited to have you as part of the SpeedLight family. Your account has been successfully created.</p>

    <h4>🔐 Your Account Details</h4>
    <p>
        Account Name: {{ $user->first_name }} {{ $user->last_name }}<br>
        Account Number: {{ $user->current_account_number }}<br>
        Account Type: {{ $user->accountType->name ?? 'N/A' }}<br>
        Currency: {{ $user->currency->name ?? 'N/A' }}
    </p>

    <p><a href="{{ url('/') }}" style="padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none;">Go to Dashboard</a></p>

    <p>Thanks,<br>The SpeedLight Bank Team</p>
</body>
</html>
