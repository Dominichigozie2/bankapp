<h3>New Deposit Preview</h3>
<p><strong>User:</strong> {{ $user->first_name }} (ID: {{ $user->id }})</p>
<p><strong>Method:</strong> {{ $request->method }}</p>
<p><strong>Amount:</strong> {{ $request->amount ?? 'N/A' }}</p>
<p><strong>User Account ID:</strong> {{ $request->user_account_id ?? 'N/A' }}</p>
<p><strong>Crypto Type ID:</strong> {{ $request->crypto_type_id ?? 'N/A' }}</p>
@if($request->hasFile('proof'))
<p><strong>Proof File:</strong> {{ $request->file('proof')->getClientOriginalName() }}</p>
@endif
