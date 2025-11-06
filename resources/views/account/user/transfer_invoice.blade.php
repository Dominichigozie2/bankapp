@extends('account.user.layout.app')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h4>Transfer Invoice: {{ $transfer->reference }}</h4>
            <p><strong>Type:</strong> {{ ucfirst($transfer->type) }}</p>
            <p><strong>Amount:</strong> ${{ number_format($transfer->amount,2) }}</p>
            <p><strong>Bank:</strong> {{ $transfer->bank_name }}</p>
            <p><strong>Account Name:</strong> {{ $transfer->account_name }}</p>
            <p><strong>Account Number:</strong> {{ $transfer->account_number }}</p>
            <p><strong>Status:</strong> {{ ucfirst($transfer->status) }}</p>
            <p><strong>Date:</strong> {{ $transfer->created_at->toDayDateTimeString() }}</p>
            <hr>
            <div>
                <a class="btn btn-primary" onclick="window.print()">Print Invoice</a>
                <a class="btn btn-secondary" href="{{ route('user.transfers.history') }}">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
