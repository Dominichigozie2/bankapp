<p>Hello {{ $user->first_name }}, </p>
<p>Your deposit is successfully submitted</p>
<p>Method: {{ $deposit->method }}</p>
<p>Amount: {{ $deposit->amount ?? 'N/A' }}</p>

