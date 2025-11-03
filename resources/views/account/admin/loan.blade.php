@extends('account.admin.layout.apps')
@section('content')
<div class="container mt-4">
    <h4>Loan Requests</h4>

    <div class="mb-3">
        <form id="setLimitForm" class="row g-2">
            @csrf
            <div class="col-auto">
                <input type="number" step="0.01" name="limit_amount" class="form-control" placeholder="Limit amount" required>
            </div>
            <div class="col-auto">
                <input type="text" name="user_id" class="form-control" placeholder="User ID (leave blank for global)">
            </div>
            <div class="col-auto">
                <button class="btn btn-secondary">Set Limit</button>
            </div>
        </form>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Repayment</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $loan->user->first_name }} {{ $loan->user->last_name }} ({{ $loan->user->email }})</td>
                <td>{{ number_format($loan->amount,2) }}</td>
                <td>{{ $loan->loan_type }}</td>
                <td>{{ $loan->duration }}</td>
                <td>{{ $loan->repayment_amount }}</td>
                <td>
                    @if($loan->status==2) <span class="badge bg-warning">Pending</span>
                    @elseif($loan->status==1) <span class="badge bg-success">Approved</span>
                    @elseif($loan->status==3) <span class="badge bg-secondary">On Hold</span>
                    @elseif($loan->status==0) <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-success btn-sm loan-action" data-id="{{ $loan->id }}" data-action="approve">Approve</button>
                    <button class="btn btn-warning btn-sm loan-action" data-id="{{ $loan->id }}" data-action="hold">Hold</button>
                    <button class="btn btn-danger btn-sm loan-action" data-id="{{ $loan->id }}" data-action="reject">Reject</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function updateLoan(id, action) {
        $.ajax({
            url: `/admin/loans/${action}/${id}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json'
        }).done(function(res) {
            if (res.success) {
                iziToast.success({
                    title: 'Success',
                    message: res.message
                });
                setTimeout(() => location.reload(), 800);
            } else {
                iziToast.error({
                    title: 'Error',
                    message: res.message
                });
            }
        }).fail(function(xhr) {
            iziToast.error({
                title: 'Error',
                message: xhr.responseJSON?.message || 'Failed'
            });
        });
    }

    // delegated handler for buttons using data-attributes to avoid inline Blade/JS parsing issues
    $(document).on('click', '.loan-action', function() {
        const id = $(this).data('id');
        const action = $(this).data('action');
        updateLoan(id, action);
    });

    $('#setLimitForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button');
        btn.prop('disabled', true);
        $.ajax({
            url: '{{ route("admin.loan.limit") }}',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function(res) {
            iziToast.success({
                title: 'Success',
                message: res.message
            });
            setTimeout(() => location.reload(), 700);
        }).fail(function(xhr) {
            iziToast.error({
                title: 'Error',
                message: xhr.responseJSON?.message || 'Failed'
            });
        }).always(function() {
            btn.prop('disabled', false);
        });
    });
</script>
@endsection