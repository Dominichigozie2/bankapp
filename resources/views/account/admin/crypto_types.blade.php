        @extends('account.admin.layout.apps')
        @section('content')


<div class="container mt-5">
    <h2>Crypto Types</h2>
    <button class="btn btn-primary mb-3" id="addCryptoBtn">Add New Crypto</button>

    <table class="table table-bordered" id="cryptoTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Network</th>
                <th>Min Balance</th>
                <th>Wallet</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cryptos as $crypto)
            <tr id="crypto-{{ $crypto->id }}">
                <td>{{ $crypto->name }}</td>
                <td>{{ $crypto->network }}</td>
                <td>${{ number_format($crypto->min_balance,2) }}</td>
                <td>{{ $crypto->wallet_address }}</td>
                <td>
                    <button class="btn btn-sm btn-info viewCrypto" data-id="{{ $crypto->id }}">View</button>
                    <button class="btn btn-sm btn-warning editCrypto" data-id="{{ $crypto->id }}">Edit</button>
                    <button class="btn btn-sm btn-danger deleteCrypto" data-id="{{ $crypto->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="cryptoModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="cryptoForm">
            @csrf
            <input type="hidden" id="crypto_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cryptoModalTitle">Add Crypto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label>Name</label><input type="text" name="name" id="name" class="form-control" required></div>
                    <div class="mb-3"><label>Network</label><input type="text" name="network" id="network" class="form-control"></div>
                    <div class="mb-3"><label>Description</label><textarea name="description" id="description" class="form-control"></textarea></div>
                    <div class="mb-3"><label>Min Balance</label><input type="number" step="0.01" name="min_balance" id="min_balance" class="form-control" required></div>
                    <div class="mb-3"><label>Wallet Address</label><input type="text" name="wallet_address" id="wallet_address" class="form-control"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(function(){

    // Open modal for create
    $('#addCryptoBtn').click(function(){
        $('#cryptoForm')[0].reset();
        $('#crypto_id').val('');
        $('#cryptoForm input, #cryptoForm textarea').prop('disabled', false); // ensure fields editable
        $('#cryptoModalTitle').text('Add Crypto');
        $('#saveBtn').show();
        $('#cryptoModal').modal('show');
    });

    // View
    $(document).on('click', '.viewCrypto', function(){
        let id = $(this).data('id');
        $.get("/admin/crypto_types/"+id, function(data){
            // Fill the form inputs and disable them
            $('#crypto_id').val(data.id);
            $('#name').val(data.name).prop('disabled', true);
            $('#network').val(data.network).prop('disabled', true);
            $('#description').val(data.description).prop('disabled', true);
            $('#min_balance').val(data.min_balance).prop('disabled', true);
            $('#wallet_address').val(data.wallet_address).prop('disabled', true);

            $('#cryptoModalTitle').text('View Crypto');
            $('#saveBtn').hide();
            $('#cryptoModal').modal('show');
        });
    });

    // Edit
    $(document).on('click', '.editCrypto', function(){
        let id = $(this).data('id');
        $.get("/admin/crypto_types/"+id, function(data){
            // Fill the form inputs and enable them
            $('#crypto_id').val(data.id);
            $('#name').val(data.name).prop('disabled', false);
            $('#network').val(data.network).prop('disabled', false);
            $('#description').val(data.description).prop('disabled', false);
            $('#min_balance').val(data.min_balance).prop('disabled', false);
            $('#wallet_address').val(data.wallet_address).prop('disabled', false);

            $('#cryptoModalTitle').text('Edit Crypto');
            $('#saveBtn').show();
            $('#cryptoModal').modal('show');
        });
    });

    // Submit form (store/update)
    $('#cryptoForm').submit(function(e){
        e.preventDefault();
        let id = $('#crypto_id').val();
        let url = id ? `/admin/crypto_types/${id}` : '/admin/crypto_types';
        let method = id ? 'POST' : 'POST'; // always POST
        let formData = $(this).serialize();

        // For update, append _method=PUT
        if(id){
            formData += '&_method=PUT';
        }

        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(res){
                iziToast.success({title:'Success', message:res.message});
                $('#cryptoModal').modal('hide');
                location.reload(); // reload table to see changes
            },
            error: function(err){
                let msg = err.responseJSON?.message || 'Error';
                iziToast.error({title:'Error', message:msg});
            }
        });
    });

    // Delete
    $(document).on('click', '.deleteCrypto', function(){
        if(!confirm('Delete this crypto?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: `/admin/crypto_types/${id}`,
            method: 'POST', // Laravel expects POST with _method DELETE
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success:function(res){
                iziToast.success({title:'Success', message:res.message});
                $('#crypto-'+id).remove();
            },
            error: function(err){
                iziToast.error({title:'Error', message:err.responseJSON?.message || 'Error'});
            }
        });
    });

});
</script>

@endsection
