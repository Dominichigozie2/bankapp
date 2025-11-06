@extends('account.user.layout.app')
@section('content')
<div class="container mt-4">
    <h4>Your Transfers</h4>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>Reference</th><th>Type</th><th>Amount</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($transfers as $t)
                    <tr>
                        <td>{{ $loop->iteration + ($transfers->currentPage()-1)*$transfers->perPage() }}</td>
                        <td>{{ $t->reference }}</td>
                        <td>{{ ucfirst($t->type) }}</td>
                        <td>${{ number_format($t->amount, 2) }}</td>
                        <td>{{ ucfirst($t->status) }}</td>
                        <td>{{ $t->created_at->toDayDateTimeString() }}</td>
                        <td><a href="{{ route('user.transfer.invoice', $t->id) }}" class="btn btn-sm btn-info">View Invoice</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $transfers->links() }}
</div>
@endsection
