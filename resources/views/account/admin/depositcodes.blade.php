      @extends('account.admin.layout.apps')
        @section('content')
<div class="container py-4">
    <h3 class="mb-4">Deposit Codes Management</h3>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Deposit Code Requirement</h5>
            <p>Require users to enter a valid deposit code before depositing.</p>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="depositCodeToggle"
                    {{ $isRequired == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="depositCodeToggle">
                    Enable Deposit Code Requirement
                </label>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Generate New Deposit Codes</h5>
            <form id="generateCodeForm">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <input type="number" name="quantity" class="form-control" placeholder="Number of codes" min="1" max="20" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">All Deposit Codes</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Used By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                    <tr>
                        <td><strong>{{ $code->code }}</strong></td>
                        <td>
                            @if($code->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($code->status == 'used')
                                <span class="badge bg-secondary">Used</span>
                            @else
                                <span class="badge bg-danger">Revoked</span>
                            @endif
                        </td>
                        <td>{{ $code->user ? $code->user->email : '-' }}</td>
                        <td>
                            @if($code->status == 'active')
                            <button class="btn btn-danger btn-sm revoke-btn" data-id="{{ $code->id }}">Revoke</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">No codes generated yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
$(function(){

    // Toggle deposit code requirement
    $('#depositCodeToggle').change(function() {
        $.post("{{ route('admin.settings.depositCodeToggle') }}", {
            value: $(this).is(':checked') ? 1 : 0,
            _token: '{{ csrf_token() }}'
        })
        .done(function(){
            iziToast.success({ title: 'Updated', message: 'Setting saved', position: 'topRight' });
        })
        .fail(function(){
            iziToast.error({ title: 'Error', message: 'Could not save setting', position: 'topRight' });
        });
    });

    // Generate new codes
    $('#generateCodeForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.depositcodes.generate') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res){
                iziToast.success({ title: 'Success', message: res.message, position: 'topRight' });
                setTimeout(()=> location.reload(), 1000);
            },
            error: function(xhr){
                iziToast.error({ title: 'Error', message: xhr.responseJSON.message || 'Failed', position: 'topRight' });
            }
        });
    });

    // Revoke code
    $('.revoke-btn').click(function(){
        let id = $(this).data('id');
        $.post("{{ url('/admin/depositcodes/revoke') }}/" + id, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(res){
            iziToast.success({ title: 'Revoked', message: res.message, position: 'topRight' });
            setTimeout(()=> location.reload(), 1000);
        })
        .fail(function(){
            iziToast.error({ title: 'Error', message: 'Could not revoke code', position: 'topRight' });
        });
    });

});
</script>
@endsection
