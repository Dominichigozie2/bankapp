@extends('account.admin.layout.apps')
@section('content')
<div class="container mt-4">
    <h4>Transfer Settings</h4>
    <form id="transferSettingsForm">
        @csrf
        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="transfers_enabled" id="transfers_enabled" {{ $settings->transfers_enabled ? 'checked' : '' }}>
            <label class="form-check-label" for="transfers_enabled">Enable Transfers</label>
        </div>

        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="cot_enabled" id="cot_enabled" {{ $settings->cot_enabled ? 'checked' : '' }}>
            <label class="form-check-label" for="cot_enabled">Require COT Code</label>
        </div>

        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="tax_enabled" id="tax_enabled" {{ $settings->tax_enabled ? 'checked' : '' }}>
            <label class="form-check-label" for="tax_enabled">Require TAX Code</label>
        </div>

        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="imf_enabled" id="imf_enabled" {{ $settings->imf_enabled ? 'checked' : '' }}>
            <label class="form-check-label" for="imf_enabled">Require IMF Code</label>
        </div>

        <div class="mb-3">
            <label>Service Charge (flat)</label>
            <input type="number" step="0.01" name="service_charge" class="form-control" value="{{ $settings->service_charge }}">
        </div>

        <div class="mb-3">
            <label>Max Transfer Amount</label>
            <input type="number" step="0.01" name="max_transfer_amount" class="form-control" value="{{ $settings->max_transfer_amount }}">
        </div>

        <div class="mb-3">
            <label>Success Message</label>
            <input type="text" name="transfer_success_message" class="form-control" value="{{ $settings->transfer_success_message }}">
        </div>

        <button class="btn btn-primary" id="saveSettings">Save</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
$('#transferSettingsForm').on('submit', function(e){
    e.preventDefault();
    $.post("{{ route('admin.transfer.update') }}", $(this).serialize(), function(res){
        iziToast.success({title:'Saved', message: res.message});
    }).fail(function(xhr){
        iziToast.error({title:'Error', message: xhr.responseJSON?.message || 'Could not save settings'});
    });
});
</script>
@endsection
