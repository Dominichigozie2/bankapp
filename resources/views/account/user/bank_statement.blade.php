@extends('account.user.layout.app')

@section('content')
<div class="container card p-4 w-60 mt-4">
    
        
        <div class="d-flex justify-content-between align-items-center w-100 mb-4">
            <h2>Bank Statement</h2>
            <a href="{{ route('account.bank.statement.download', request()->query()) }}" class="btn btn-success">
                <i class="bi bi-download"></i> Download
            </a>
        </div>
    
    {{-- Filter Form --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">to</label>
            <input type="date" name="to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="Deposit" @selected($typeFilter=='Deposit' )>Deposit</option>
                <option value="Local Transfer" @selected($typeFilter=='Local Transfer' )>Local Transfer</option>
                <option value="International Transfer" @selected($typeFilter=='International Transfer' )>International Transfer</option>
                <option value="Self Transfer" @selected($typeFilter=='Self Transfer' )>Self Transfer</option>
                <option value="Loan" @selected($typeFilter=='Loan' )>Loan</option>
            </select>
        </div>
        <div class="col-md-3">
            
            <button class="btn btn-primary" type="submit">Filter</button>
        </div>

    </form>

    {{-- Transactions Table --}}
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><i class="bi bi-calendar-date"></i> Time</th>
                <th>Type</th>
                <th>Details</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
            @php
            // Colors and icons
            $statusColor = $tx['status'] === 'pending' ? 'text-warning fw-bold' : 'text-success fw-bold';
            $isTransfer = in_array($tx['type'], ['Local Transfer','International Transfer']);
            $amountColor = ($tx['type'] === 'Deposit' || $tx['type'] === 'Self Transfer') ? 'text-success' : ($isTransfer ? 'text-danger' : 'text-muted');
            $amountSign = ($tx['type'] === 'Deposit' || $tx['type'] === 'Self Transfer') ? '+' : ($isTransfer ? '-' : '');
            @endphp
            <tr>
                <td><i class="bi bi-calendar-date me-1"></i>{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y, H:i') }}</td>
                <td>{{ $tx['type'] }}</td>
                <td>{{ $tx['details'] }}</td>
                <td class="{{ $amountColor }}">{{ $amountSign }}{{ number_format($tx['amount'], 2) }}</td>
                <td class="{{ $statusColor }}">{{ ucfirst($tx['status']) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No transactions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Adjust pagination arrows */
    .page-link {
        font-size: 0.9rem;
        /* smaller size */
        padding: 0.3rem 0.6rem;
    }

    /* Optional: hover effects for table rows */
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
@endsection