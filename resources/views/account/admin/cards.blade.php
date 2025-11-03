@extends('account.admin.layout.apps')
@section('content')
        <div class="page-content">
            <div class="container">
                <h4>All Card Requests</h4>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Serial</th>
                            <th>Account</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cards as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $c->card_name }}</td>
                            <td>{{ $c->serial_key }}</td>
                            <td>{{ $c->payment_account }}</td>
                            <td>
                                @if($c->card_status == 2) <span class="badge bg-warning">Pending</span>
                                @elseif($c->card_status == 1) <span class="badge bg-success">Approved</span>
                                @elseif($c->card_status == 0) <span class="badge bg-danger">Rejected</span>
                                @elseif($c->card_status == 3) <span class="badge bg-secondary">On Hold</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm card-action" data-id="{{ $c->id }}" data-action="approve">Approve</button>
                                <button class="btn btn-warning btn-sm card-action" data-id="{{ $c->id }}" data-action="hold">Hold</button>
                                <button class="btn btn-danger btn-sm card-action" data-id="{{ $c->id }}" data-action="reject">Reject</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @section('scripts')
        <script>
            function updateStatus(id, action) {
                $.ajax({
                    url: '/admin/cards/' + action + '/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(res) {
                        iziToast.success({
                            title: 'Success',
                            message: res.message
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Failed to update card.'
                        });
                    }
                });
            }

            $(document).on('click', '.card-action', function() {
                var id = $(this).data('id');
                var action = $(this).data('action');
                updateStatus(id, action);
            });
        </script>
        @endsection
        @endsection