@extends('account.user.layout.app')

@section('content')

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="receiptModalLabel">Transaction Receipt</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Date:</strong> <span id="receiptDate"></span></p>
        <p><strong>Type:</strong> <span id="receiptType"></span></p>
        <p><strong>Details:</strong> <span id="receiptDetails"></span></p>
        <p><strong>Amount:</strong> ₦<span id="receiptAmount"></span></p>
        <p><strong>Status:</strong> <span id="receiptStatus"></span></p>
      </div>
      <div class="modal-footer">
        <a id="downloadReceiptBtn" href="#" class="btn btn-success"><i class="bi bi-download"></i> Download PDF</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="Deposit" @selected($typeFilter=='Deposit')>Deposit</option>
                <option value="Local Transfer" @selected($typeFilter=='Local Transfer')>Local Transfer</option>
                <option value="International Transfer" @selected($typeFilter=='International Transfer')>International Transfer</option>
                <option value="Self Transfer" @selected($typeFilter=='Self Transfer')>Self Transfer</option>
                <option value="Loan" @selected($typeFilter=='Loan')>Loan</option>
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
                <th>Time</th>
                <th>Type</th>
                <th>Details</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
            @php
                $statusText = $tx['status']; 
                $statusColor = $statusText === 'Successful' ? 'text-success fw-bold' : 'text-danger fw-bold';

                $isTransfer = in_array($tx['type'], ['Local Transfer','International Transfer']);
                $amountColor = ($tx['type'] === 'Deposit' || $tx['type'] === 'Self Transfer' || $tx['type'] === 'Loan') ? 'text-success' : ($isTransfer ? 'text-danger' : 'text-muted');
                $amountSign = ($tx['type'] === 'Deposit' || $tx['type'] === 'Self Transfer' || $tx['type'] === 'Loan') ? '+' : ($isTransfer ? '-' : '');
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y, H:i') }}</td>
                <td>{{ $tx['type'] }}</td>
                <td>{{ $tx['details'] }}</td>
                <td class="{{ $amountColor }}">
                    {{ $amountSign }}{{ $tx['amount'] !== null ? number_format($tx['amount'],2) : '' }}
                </td>
                <td class="{{ $statusColor }}">{{ $statusText }}</td>
                <td>
                    <button
                        class="btn btn-sm btn-primary view-receipt-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#receiptModal"
                        data-id="{{ $tx['id'] }}"
                        data-type="{{ $tx['type'] }}"
                        data-details="{{ $tx['details'] }}"
                        data-amount="{{ $tx['amount'] ?? '' }}"
                        data-status="{{ $statusText }}"
                        data-date="{{ \Carbon\Carbon::parse($tx['created_at'])->format('d M Y, H:i') }}">
                        <i class="bi bi-eye"></i> View
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No transactions found.</td>
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
.page-link {
    font-size: 0.9rem;
    padding: 0.3rem 0.6rem;
}
.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}
</style>

<script>
document.querySelectorAll('.view-receipt-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const type = btn.dataset.type;
        const details = btn.dataset.details;
        const amount = btn.dataset.amount;
        const status = btn.dataset.status;
        const date = btn.dataset.date;

        document.getElementById('receiptDate').innerText = date;
        document.getElementById('receiptType').innerText = type;
        document.getElementById('receiptDetails').innerText = details;
        document.getElementById('receiptAmount').innerText = amount ? parseFloat(amount).toFixed(2) : '';
        document.getElementById('receiptStatus').innerText = status;

        document.getElementById('downloadReceiptBtn').href = '/bank-statement/receipt/download/' + id;
    });
});
</script>
@endsection
