        @extends('account.admin.layout.apps')
        @section('content')

        <div class="page-content">

            <div class="container">
                <h4 class="mb-4">All Deposits</h4>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Account Type</th>
                            <th>Crypto Type</th>
                            <th>Deposit Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deposits as $deposit)
                        <tr id="deposit-{{ $deposit->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $deposit->user->first_name }} {{ $deposit->user->last_name }}</td>
                            <td>${{ number_format($deposit->amount, 2) }}</td>
                            <td>{{ $deposit->accountType->name ?? 'N/A' }}</td>
                            <td>{{ $deposit->cryptoType->name ?? '-' }}</td>
                            <td>{{ ucfirst($deposit->deposit_type) }}</td>
                            <td>
                                <span class="badge bg-{{ $deposit->status == 'approved' ? 'success' : ($deposit->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($deposit->status) }}
                                </span>
                            </td>
                            <td>{{ $deposit->dep_date ? \Carbon\Carbon::parse($deposit->dep_date)->format('d M, Y') : 'N/A' }}</td>
                            <td>
                                @if ($deposit->status == 'pending')
                                <button class="btn btn-success btn-sm approve" data-id="{{ $deposit->id }}">Approve</button>
                                <button class="btn btn-danger btn-sm reject" data-id="{{ $deposit->id }}">Reject</button>
                                @else
                                <span class="text-muted">Processed</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $deposits->links() }}
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Approve Deposit
                $('.approve').on('click', function() {
                    let id = $(this).data('id');

                    $.ajax({
                        url: `/admin/deposit/approve/${id}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            iziToast.success({
                                title: 'Approved',
                                message: response.message,
                                position: 'topRight'
                            });
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Error',
                                message: xhr.responseJSON?.message || 'Something went wrong',
                                position: 'topRight'
                            });
                        }
                    });
                });

                // Reject Deposit
                $('.reject').on('click', function() {
                    let id = $(this).data('id');

                    $.ajax({
                        url: `/admin/deposit/reject/${id}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            iziToast.error({
                                title: 'Rejected',
                                message: response.message,
                                position: 'topRight'
                            });
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Error',
                                message: xhr.responseJSON?.message || 'Something went wrong',
                                position: 'topRight'
                            });
                        }
                    });
                });
            });
        </script>
        @endsection