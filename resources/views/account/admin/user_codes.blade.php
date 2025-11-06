@extends('account.admin.layout.apps')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Manage User Codes</h4>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Transfer Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->transfer_restricted)
                    <span class="badge bg-danger">Restricted</span>
                    @else
                    <span class="badge bg-success">Allowed</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="javascript:void(0)"
                    class="btn btn-primary btn-sm popbtn"
                    data-id="{{ $user->id }}"
                     data-name="{{ $user->first_name }} {{ $user->last_name }}"
                    > Edit Codes
                    </a>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCodeForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Codes for <span id="userName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="mb-3">
                        <label>COT Code</label>
                        <input type="text" class="form-control" name="cot_code" id="cot_code">
                    </div>
                    <div class="mb-3">
                        <label>TAX Code</label>
                        <input type="text" class="form-control" name="tax_code" id="tax_code">
                    </div>
                    <div class="mb-3">
                        <label>IMF Code</label>
                        <input type="text" class="form-control" name="imf_code" id="imf_code">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="transfer_restricted" name="transfer_restricted">
                        <label class="form-check-label">Restrict Transfers</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){
    // When Edit is clicked
    $('.popbtn').on('click', function(){
        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#user_id').val(id);
        $('#userName').text(name);

        // Fetch user codes
        $.get(`/admin/codes/${id}/data`, function(data){
            $('#cot_code').val(data.cot_code);
            $('#tax_code').val(data.tax_code);
            $('#imf_code').val(data.imf_code);
            $('#transfer_restricted').prop('checked', data.transfer_restricted);

            // Show modal only after data is loaded
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });
    });

    // Save updates
    $('#editCodeForm').on('submit', function(e){
        e.preventDefault();
        let id = $('#user_id').val();

        $.post(`/admin/codes/${id}/update`, $(this).serialize())
        .done(function(res){
            iziToast.success({
                title: 'Success',
                message: res.message,
                position: 'topRight'
            });
            $('#editModal').modal('hide');
            setTimeout(() => location.reload(), 5000);
        })
        .fail(function(){
            iziToast.error({
                title: 'Error',
                message: 'Something went wrong.',
                position: 'topRight'
            });
        });
    });
});
</script>
@endsection
