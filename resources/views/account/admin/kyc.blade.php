@extends('account.admin.layout.apps')
@section('content')
<div class="container py-5">
    <h3 class="mb-4">KYC Management</h3>

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>ID Number</th>
                <th>Front ID</th>
                <th>Back ID</th>
                <th>Address Proof</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr id="user-row-{{ $user->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->id_no }}</td>
                    <td><a href="{{ asset('storage/' . $user->idfront) }}" target="_blank">View</a></td>
                    <td><a href="{{ asset('storage/' . $user->idback) }}" target="_blank">View</a></td>
                    <td><a href="{{ asset('storage/' . $user->addressproof) }}" target="_blank">View</a></td>
                    <td>
                        <span class="badge 
                            {{ $user->kyc_status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                            {{ ucfirst($user->kyc_status) }}
                        </span>
                    </td>
                    <td>
                        @if ($user->kyc_status == 'pending')
                            <button class="btn btn-success btn-sm approve-btn" data-id="{{ $user->id }}">Approve</button>
                            <button class="btn btn-danger btn-sm reject-btn" data-id="{{ $user->id }}">Reject</button>
                        @else
                            <button class="btn btn-danger btn-sm reject-btn" data-id="{{ $user->id }}">Deactivate</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No pending KYC submissions.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    function handleKycAction(url, id, successMessage, toastType = 'success') {
        $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
            iziToast[toastType]({
                title: 'Success',
                message: res.message
            });
            setTimeout(() => location.reload(), 1500); // Refresh after 1.5 seconds
        }).fail(() => {
            iziToast.error({
                title: 'Error',
                message: 'Something went wrong!'
            });
        });
    }

    // Approve KYC
    $('.approve-btn').click(function() {
        let id = $(this).data('id');
        handleKycAction(`{{ url('/admin/kyc/approve') }}/${id}`, id, 'KYC approved successfully!', 'success');
    });

    // Reject KYC
    $('.reject-btn').click(function() {
        let id = $(this).data('id');
        handleKycAction(`{{ url('/admin/kyc/reject') }}/${id}`, id, 'KYC rejected successfully!', 'info');
    });
});
</script>
@endsection
