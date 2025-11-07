@extends('account.user.layout.app')
@section('content')

<div class="container card mt-5 p-4">
    <h4>All Transfers</h4>

    <table class="table mt-5 p-4">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Reference</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transfers as $t)
            <tr id="transfer-{{ $t->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->user?->first_name }} {{ $t->user?->last_name }} ({{ $t->user?->email }})</td>
                <td>{{ ucfirst($t->type) }}</td>
                <td>{{ number_format($t->amount,2) }}</td>
                <td><span class="badge bg-{{ $t->status === 'pending' ? 'warning' : ($t->status === 'success' ? 'success' : 'danger') }}">{{ $t->status }}</span></td>
                <td>{{ $t->reference }}</td>
                <td>
                    @if($t->status === 'pending')
                        <button class="btn btn-sm btn-success approve-btn" data-id="{{ $t->id }}">Approve</button>
                        <button class="btn btn-sm btn-danger reject-btn" data-id="{{ $t->id }}">Reject</button>
                    @else
                        <small>Processed</small>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
$(document).on('click', '.approve-btn', function(){
    const id = $(this).data('id');
    if (!confirm('Approve this transfer?')) return;
    $.post("{{ url('admin/transfers') }}/" + id + "/approve", {_token: '{{ csrf_token() }}'}, function(res){
        if (res.success) {
            iziToast.success({title:'OK', message: res.message});
            setTimeout(()=>location.reload(),800);
        } else iziToast.error({message: res.message || 'Error'});
    }).fail(function(xhr){
        iziToast.error({message: xhr.responseJSON?.message || 'Error approving'});
    });
});

$(document).on('click', '.reject-btn', function(){
    const id = $(this).data('id');
    if (!confirm('Reject this transfer?')) return;
    $.post("{{ url('admin/transfers') }}/" + id + "/reject", {_token: '{{ csrf_token() }}'}, function(res){
        if (res.success) {
            iziToast.success({title:'OK', message: res.message});
            setTimeout(()=>location.reload(),800);
        } else iziToast.error({message: res.message || 'Error'});
    }).fail(function(xhr){
        iziToast.error({message: xhr.responseJSON?.message || 'Error rejecting'});
    });
});
</script>
@endpush
@endsection
