@extends('account.admin.layout.app')
@section('content')

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4>Transfer Settings</h4>
            <form action="{{ route('admin.transfer.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="transfers_enabled" {{ $settings->transfers_enabled ? 'checked' : '' }}>
                    <label class="form-check-label">Enable Transfers</label>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="cot_enabled" {{ $settings->cot_enabled ? 'checked' : '' }}>
                    <label class="form-check-label">Require COT Code</label>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="tax_enabled" {{ $settings->tax_enabled ? 'checked' : '' }}>
                    <label class="form-check-label">Require TAX Code</label>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="imf_enabled" {{ $settings->imf_enabled ? 'checked' : '' }}>
                    <label class="form-check-label">Require IMF Code</label>
                </div>

                <div class="mt-3">
                    <label>Service Charge ($)</label>
                    <input type="number" step="0.01" name="service_charge" class="form-control" value="{{ $settings->service_charge }}">
                </div>

                <div class="mt-3">
                    <label>Transfer Limit ($)</label>
                    <input type="number" step="0.01" name="transfer_limit" class="form-control" value="{{ $settings->transfer_limit }}">
                </div>

                <button type="submit" class="btn btn-primary mt-3 w-100">Save Changes</button>
            </form>
        </div>
    </div>
</div>

@endsection
