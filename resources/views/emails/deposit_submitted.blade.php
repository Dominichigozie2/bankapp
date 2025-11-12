<p>New deposit submitted by {{ $user->name }} (ID: {{ $user->id }})</p>
<p>Method: {{ $deposit->method }}</p>
<p>Amount: {{ $deposit->amount ?? 'N/A' }}</p>
<p>Account Type ID: {{ $deposit->account_type_id ?? 'N/A' }}</p>
<p>Deposit ID: {{ $deposit->id }}</p>
