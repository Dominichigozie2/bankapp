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
                            <th>Proof</th>
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
                            <td>{{ $deposit->userAccount?->accountType?->name ?? 'N/A' }}</td>
                            <td>{{ $deposit->cryptoType->name ?? '-' }}</td>
                            <td>{{ ucfirst($deposit->method) }}</td>

                            {{-- ✅ Proof File Preview --}}
                            <td>
                                @if ($deposit->proof_url)
                                @if (Str::endsWith($deposit->proof_url, ['.jpg', '.jpeg', '.png']))
                                <a href="{{ asset('storage/' . $deposit->proof_url) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $deposit->proof_url) }}"
                                        alt="Proof" width="20" height="20" class="rounded">
                                </a>
                                @elseif (Str::endsWith($deposit->proof_url, '.pdf'))
                                <a href="{{ asset('storage/' . $deposit->proof_url) }}" target="_blank" class="btn btn-sm btn-info">
                                    View PDF
                                </a>
                                @else
                                <span class="text-muted">No preview</span>
                                @endif
                                @else
                                <span class="text-muted">No proof</span>
                                @endif
                            </td>

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
                                <button class="btn btn-primary btn-sm edit-deposit" data-id="{{ $deposit->id }}" data-amount="{{ $deposit->amount }}">Edit</button>

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

        <div class="modal fade" id="editDepositModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Deposit</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editDepositForm">
                        <div class="modal-body">
                            <input type="hidden" id="editDepositId" name="id">

                            <div class="mb-3">
                                <label>Amount</label>
                                <input type="number" class="form-control" id="editAmount" name="amount" required>
                            </div>

                            <div class="mb-3">
                                <label>Account Type</label>
                                <select name="user_account_id" id="editUserAccount" class="form-select" required>
                                    @foreach(\App\Models\UserAccount::all() as $ua)
                                    <option value="{{ $ua->id }}">{{ $ua->accountType?->name ?? 'N/A' }} - {{ $ua->user->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @endsection

        @section('scripts')
        <script>
            $(document).ready(function() {
                console.log('Admin deposit page script loaded.');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                $('.approve').click(function() {
                    let id = $(this).data('id');
                    console.log('Approve clicked:', id); // 👈 Debug line

                    $.post(`/admin/deposit/approve/${id}`, {}, function(response) {
                        console.log('Response:', response); // 👈 Debug line
                        iziToast.success({
                            title: 'Approved',
                            message: response.message,
                            position: 'topRight'
                        });
                        setTimeout(() => location.reload(), 1000);
                    }).fail(function(xhr) {
                        console.log('Error:', xhr); // 👈 Debug line
                        iziToast.error({
                            title: 'Error',
                            message: xhr.responseJSON?.message || 'Something went wrong',
                            position: 'topRight'
                        });
                    });
                });

                $('.reject').click(function() {
                    let id = $(this).data('id');
                    console.log('Reject clicked:', id); // 👈 Debug line

                    $.post(`/admin/deposit/reject/${id}`, {}, function(response) {
                        console.log('Response:', response);
                        iziToast.error({
                            title: 'Rejected',
                            message: response.message,
                            position: 'topRight'
                        });
                        setTimeout(() => location.reload(), 1000);
                    }).fail(function(xhr) {
                        console.log('Error:', xhr);
                        iziToast.error({
                            title: 'Error',
                            message: xhr.responseJSON?.message || 'Something went wrong',
                            position: 'topRight'
                        });
                    });
                });


                $(document).ready(function() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    $('.edit-deposit').click(function() {
                        let id = $(this).data('id');
                        let amount = $(this).data('amount');
                        let user_account = $(this).data('user_account');

                        $('#editDepositId').val(id);
                        $('#editAmount').val(amount);
                        $('#editUserAccount').val(user_account);
                        $('#editDepositModal').modal('show');
                    });

                    $('#editDepositForm').submit(function(e) {
                        e.preventDefault();
                        let id = $('#editDepositId').val();
                        let amount = $('#editAmount').val();
                        let user_account_id = $('#editUserAccount').val();

                        $.post(`/admin/deposit/edit/${id}`, {
                            amount,
                            user_account_id
                        }, function(res) {
                            iziToast.success({
                                title: 'Success',
                                message: res.message,
                                position: 'topRight'
                            });
                            $('#editDepositModal').modal('hide');
                            setTimeout(() => location.reload(), 1000);
                        }).fail(function(xhr) {
                            iziToast.error({
                                title: 'Error',
                                message: xhr.responseJSON?.message || 'Something went wrong',
                                position: 'topRight'
                            });
                        });
                    });
                });

            });
        </script>
        @endsection