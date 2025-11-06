@extends('account.admin.layout.apps')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="card row">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Manage Account Types</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                        <i class="bi bi-plus-circle"></i> Add Account Type
                    </button>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Min Balance</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($types as $index => $type)
                                <tr id="type-row-{{ $type->id }}">
                                    <td>{{ $types->firstItem() + $index }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->slug }}</td>
                                    <td>${{ number_format($type->min_balance, 2) }}</td>
                                    <td>{{ $type->description ?? '—' }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm deleteType" data-id="{{ $type->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No account types found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $types->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addTypeForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Account Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Minimum Balance</label>
                        <input type="number" step="0.01" name="min_balance" class="form-control" placeholder="0.00">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Type</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    // ✅ Add account type
    $('#addTypeForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.accounttypes.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                iziToast.success({
                    title: 'Success',
                    message: res.message
                });
                $('#addTypeModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'Failed to add account type';
                iziToast.error({
                    title: 'Error',
                    message: msg
                });
            }
        });
    });

    // ❌ Delete account type
    $(document).on('click', '.deleteType', function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this account type?')) {
            $.ajax({
                url: "{{ url('admin/accounttypes/delete') }}/" + id,
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    if (res.success) {
                        $('#type-row-' + id).remove();
                        iziToast.success({
                            title: 'Deleted',
                            message: res.message
                        });
                    }
                },
                error: function() {
                    iziToast.error({
                        title: 'Error',
                        message: 'Could not delete account type'
                    });
                }
            });
        }
    });
});
</script>
@endsection
