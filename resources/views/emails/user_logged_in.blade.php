# User Login Alert

User **{{ $user->first_name }} {{ $user->last_name }}** has just logged in.

- Account Number: {{ $user->current_account_number }}
- Email: {{ $user->email }}
- Phone: {{ $user->phone }}
- Time: {{ now()->toDateTimeString() }}
