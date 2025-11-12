@extends('account.admin.layout.apps')
@section('content')

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4>Transfer & Deposit Settings</h4>
            <form id="transferSettingsForm">
                @csrf

                <div class="form-check mt-2">
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

                <hr>

                <div class="mt-3">
                    <label>Service Charge ($)</label>
                    <input type="number" step="0.01" name="service_charge" class="form-control" value="{{ $settings->service_charge }}">
                </div>

                <div class="mt-3">
                    <label>Transfer Limit ($)</label>
                    <input type="number" step="0.01" name="max_transfer_amount" class="form-control" value="{{ $settings->max_transfer_amount }}">
                </div>

                <div class="mt-3">
                    <label>Global COT Code</label>
                    <input type="text" name="global_cot_code" class="form-control" value="{{ $settings->global_cot_code }}">
                </div>

                <div class="mt-3">
                    <label>Global TAX Code</label>
                    <input type="text" name="global_tax_code" class="form-control" value="{{ $settings->global_tax_code }}">
                </div>

                <div class="mt-3">
                    <label>Global IMF Code</label>
                    <input type="text" name="global_imf_code" class="form-control" value="{{ $settings->global_imf_code }}">
                </div>

                <div class="mt-3">
                    <label>Transfer Page Instruction</label>
                    <textarea name="transfer_instruction" class="form-control" rows="2">{{ $settings->transfer_instruction }}</textarea>
                </div>

                <div class="mt-3">
                    <label>Deposit Page Instruction</label>
                    <textarea name="deposit_instruction" class="form-control" rows="2">{{ $settings->deposit_instruction }}</textarea>
                </div>

                <div class="mt-3">
                    <label>COT Message</label>
                    <textarea name="cot_message" class="form-control" rows="2">{{ $settings->cot_message }}</textarea>
                </div>

                <div class="mt-3">
                    <label>TAX Message</label>
                    <textarea name="tax_message" class="form-control" rows="2">{{ $settings->tax_message }}</textarea>
                </div>

                <div class="mt-3">
                    <label>IMF Message</label>
                    <textarea name="imf_message" class="form-control" rows="2">{{ $settings->imf_message }}</textarea>
                </div>

                <hr>

                {{-- 🟢 New Deposit Messages Section --}}
                <h5 class="mt-4 text-primary">Deposit Code Messages</h5>

                <div class="mt-3">
                    <label>COT Deposit Message</label>
                    <textarea name="cot_dep_message" class="form-control" rows="2">{{ $settings->cot_dep_message }}</textarea>
                </div>

                <div class="mt-3">
                    <label>TAX Deposit Message</label>
                    <textarea name="tax_dep_message" class="form-control" rows="2">{{ $settings->tax_dep_message }}</textarea>
                </div>

                <div class="mt-3">
                    <label>IMF Deposit Message</label>
                    <textarea name="imf_dep_message" class="form-control" rows="2">{{ $settings->imf_dep_message }}</textarea>
                </div>

                <hr>

                <div class="mt-3">
                    <label>Transfer Success Message</label>
                    <input type="text" name="transfer_success_message" class="form-control" value="{{ $settings->transfer_success_message }}">
                </div>

                <div class="mt-3">
                    <label>Admin Notification Email</label>
                    <input type="email" name="admin_email" class="form-control" value="{{ $settings->admin_email }}">
                    <small class="text-muted">This email will receive all user transaction notifications.</small>
                </div>


                <button type="submit" class="btn btn-primary mt-3 w-100">Save Changes</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#transferSettingsForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.transfer_settings.update') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                iziToast.success({
                    title: 'Saved',
                    message: res.message,
                    position: 'topRight'
                });
            },
            error: function(xhr) {
                iziToast.error({
                    title: 'Error',
                    message: xhr.responseJSON?.message || 'Something went wrong',
                    position: 'topRight'
                });
            }
        });
    });
</script>
@endsection