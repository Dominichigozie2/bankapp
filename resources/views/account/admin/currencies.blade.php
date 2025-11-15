 @extends('account.admin.layout.apps')
        @section('content')

<div class="container mt-4 card p-md-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Currencies</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#currencyModal" onclick="openAddModal()">Add Currency</button>
    </div>

    <table class="table table-bordered" id="currencyTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Symbol</th>
                <th>Rate</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($currencies as $currency)
            <tr id="currency-{{ $currency->id }}">
                <td>{{ $currency->id }}</td>
                <td class="name">{{ $currency->name }}</td>
                <td class="code">{{ $currency->code }}</td>
                <td class="symbol">{{ $currency->symbol ?? '-' }}</td>
                <td class="rate">{{ $currency->rate }}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="openEditModal({{ $currency->id }})">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteCurrency({{ $currency->id }})">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="currencyForm">
        @csrf
        <input type="hidden" id="currency_id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="currencyModalLabel">Add Currency</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Currency Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="code" class="form-label">Currency Code</label>
                    <input type="text" id="code" name="code" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="symbol" class="form-label">Symbol (optional)</label>
                    <input type="text" id="symbol" name="symbol" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="rate" class="form-label">Rate</label>
                    <input type="number" step="0.000001" id="rate" name="rate" class="form-control" required>
                </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Currency</button>
          </div>
        </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
let formMethod = 'POST';

function openAddModal() {
    formMethod = 'POST';
    $('#currencyForm')[0].reset();
    $('#currency_id').val('');
    $('#currencyModalLabel').text('Add Currency');
}

function openEditModal(id) {
    const row = $(`#currency-${id}`);
    $('#name').val(row.find('.name').text());
    $('#code').val(row.find('.code').text());
    $('#symbol').val(row.find('.symbol').text() === '-' ? '' : row.find('.symbol').text());
    $('#rate').val(row.find('.rate').text());
    $('#currency_id').val(id);
    $('#currencyModalLabel').text('Edit Currency');
    formMethod = 'PUT';
    new bootstrap.Modal(document.getElementById('currencyModal')).show();
}

$('#currencyForm').submit(function(e) {
    e.preventDefault();
    const id = $('#currency_id').val();
    const url = formMethod === 'POST' ? "{{ route('admin.currencies.store') }}" : `/admin/currencies/${id}`;
    $.ajax({
        url: url,
        method: formMethod,
        data: $(this).serialize(),
        success: function(res) {
            if(res.success){
                iziToast.success({title: 'Success', message: 'Currency saved successfully.'});
                location.reload();
            } else {
                iziToast.error({title: 'Error', message: Object.values(res.errors).join(', ')});
            }
        }
    });
});

function deleteCurrency(id){
    if(!confirm('Are you sure you want to delete this currency?')) return;
    $.ajax({
        url: `/admin/currencies/${id}`,
        method: 'DELETE',
        data: {_token: '{{ csrf_token() }}'},
        success: function(res){
            if(res.success){
                iziToast.success({title: 'Deleted', message: 'Currency deleted.'});
                $(`#currency-${id}`).remove();
            } else {
                iziToast.error({title: 'Error', message: 'Could not delete currency.'});
            }
        }
    });
}
</script>
@endsection
