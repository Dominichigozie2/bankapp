    @extends('account.admin.layout.apps')
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
                    <button class="btn btn-sm btn-info view-btn" data-transfer='@json($t)'>View</button>
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

<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transfer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Type:</strong> <span id="modalType"></span></p>
        <p><strong>Amount:</strong> $<span id="modalAmount"></span></p>
        <p><strong>From Account:</strong> <span id="modalFrom"></span></p>
        <p><strong>To Account:</strong> <span id="modalTo"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Reference:</strong> <span id="modalRef"></span></p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success approve-btn" id="modalApprove" data-id="">Approve</button>
        <button class="btn btn-danger reject-btn" id="modalReject" data-id="">Reject</button>
      </div>
    </div>
  </div>
</div>


@endsection


@section('scripts')
<script>
   
  
   $(document).on('click', '.view-btn', function() {
    const data = $(this).data('transfer');

    $('#modalType').text(data.type);
    $('#modalAmount').text(parseFloat(data.amount).toFixed(2));

    // Pull account names safely
    const fromName = data.meta?.from_account_name || 'N/A';
    const toName = data.meta?.to_account_name || data.account_name || 'N/A';

    $('#modalFrom').text(fromName);
    $('#modalTo').text(toName);

    $('#modalStatus').text(data.status);
    $('#modalRef').text(data.reference);

    $('#modalApprove').data('id', data.id);
    $('#modalReject').data('id', data.id);

    $('#transferModal').modal('show');
});



$(document).on('click', '.approve-btn', function(){
    const id = $(this).data('id');
    $.post(`/admin/transfers/${id}/approve`, {_token: '{{ csrf_token() }}'}, function(res){
        if(res.success){
            iziToast.success({title:'OK', message:res.message});
            setTimeout(()=>location.reload(),1000);
        } else {
            iziToast.error({message:res.message});
        }
    }).fail(xhr=>{
        iziToast.error({message:xhr.responseJSON?.message || 'Error approving transfer'});
    });
});

$(document).on('click', '.reject-btn', function(){
    const id = $(this).data('id');
    $.post(`/admin/transfers/${id}/reject`, {_token: '{{ csrf_token() }}'}, function(res){
        if(res.success){
            iziToast.success({title:'OK', message:res.message});
            setTimeout(()=>location.reload(),1000);
        } else {
            iziToast.error({message:res.message});
        }
    }).fail(xhr=>{
        iziToast.error({message:xhr.responseJSON?.message || 'Error rejecting transfer'});
    });
});

</script>
@endsection