@php
use App\Models\AdminSetting;
$settings = AdminSetting::first();
@endphp

@extends('account.user.layout.app')
@section('content')

<div class="page-content">
    <div class="container card p-4 w-60 mt-4">
        <h4>Make a Deposit</h4>

        <div class="mb-3">
            <label for="method">Deposit Method</label>
            <select id="method" class="form-control">
                <option value="">Select Method</option>
                <option value="cheque">Cheque</option>
                <option value="mobile">Mobile/Crypto</option>
            </select>
        </div>

        {{-- Cheque Form --}}
        <form id="chequeForm" enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="hidden" name="method" value="cheque">
            <div class="mb-3">
                <label>Upload Cheque Image</label>
                <input type="file" name="proof" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Cheque Deposit</button>
        </form>

        {{-- Mobile Form --}}
        <form id="mobileForm" enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="hidden" name="method" value="mobile">
            <div class="mb-3">
                <label>Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Account Type</label>
                <select name="account_type_id" class="form-control" required>
                    <option value="">Select Account</option>
                    @foreach($userAccounts as $acct)
                        <option value="{{ $acct->id }}" {{ $acct->is_active ? 'selected' : '' }}>
                            {{ $acct->accountType->name }} {{ $acct->account_number ? '- '.$acct->account_number : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Crypto Type</label>
                <select name="crypto_type_id" id="crypto_type_id" class="form-control">
                    <option value="">Select Crypto</option>
                    @foreach($cryptoTypes as $crypto)
                        <option value="{{ $crypto->id }}" data-wallet="{{ $crypto->wallet_address }}">{{ $crypto->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3" id="networkDiv" style="display:none;">
                <label>Network / Wallet Address</label>
                <input type="text" id="network" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label>Upload Proof of Payment</label>
                <input type="file" name="proof" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit Mobile Deposit</button>
        </form>
    </div>
</div>

{{-- Instruction Modal (shows when any code enabled) --}}
@if($settings && ($settings->cot_enabled || $settings->tax_enabled || $settings->imf_enabled))
<div class="modal fade" id="instructionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Important</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {!! $settings->cot_message ?? 'Please request for your deposit codes before proceeding.' !!}
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary">Request Code</a>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Codes verification modal (asks for required codes) --}}
<div class="modal fade" id="codeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Enter Required Codes</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted"> {!! $settings->deposit_instruction ?? 'Provide the codes required for this deposit.' !!}</p>

                @if($settings->cot_enabled)
                <div class="mb-3">
                    <label>COT Code</label>
                    <input type="text" id="cot_code" class="form-control" placeholder="COT Code">
                    <small id="cot_err" class="text-danger d-block"></small>
                </div>
                @endif


                @if($settings->tax_enabled)
                <div class="mb-3">
                    <label>TAX Code</label>
                    <input type="text" id="tax_code" class="form-control" placeholder="TAX Code">
                    <small id="tax_err" class="text-danger d-block"></small>
                </div>
                @endif

                @if($settings->imf_enabled)
                <div class="mb-3">
                    <label>IMF Code</label>
                    <input type="text" id="imf_code" class="form-control" placeholder="IMF Code">
                    <small id="imf_err" class="text-danger d-block"></small>
                </div>
                @endif

                <button id="verifyAllCodes" class="btn btn-primary w-100">Verify Codes</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
$(function(){
    // show instruction modal if any code enabled
    @if($settings && ($settings->cot_enabled || $settings->tax_enabled || $settings->imf_enabled))
        var insModal = new bootstrap.Modal(document.getElementById('instructionModal'));
        insModal.show();
    @endif

    // form toggles
    $('#method').change(function(){
        $('#chequeForm, #mobileForm').hide();
        const v = $(this).val();
        if (v === 'cheque') $('#chequeForm').show();
        if (v === 'mobile') $('#mobileForm').show();
    });

    // crypto wallet
    $('#crypto_type_id').change(function(){
        const w = $(this).find(':selected').data('wallet');
        if (w) { $('#networkDiv').show(); $('#network').val(w); } else { $('#networkDiv').hide(); $('#network').val(''); }
    });

    // universal submit
    $('#chequeForm, #mobileForm').on('submit', function(e){
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);
        const method = fd.get('method');

        // if any codes enabled -> show code modal first
        @if($settings->cot_enabled || $settings->tax_enabled || $settings->imf_enabled)
            $('#codeModal').modal('show');

            $('#verifyAllCodes').off('click').on('click', function(){
                // clear field errors
                $('#cot_err, #tax_err, #imf_err').text('');

                const payload = {
                    _token: '{{ csrf_token() }}',
                    cot_code: $('#cot_code').val() ?? '',
                    tax_code: $('#tax_code').val() ?? '',
                    imf_code: $('#imf_code').val() ?? ''
                };

                $.ajax({
                    url: "{{ route('user.deposit.verifyMultipleCodes') }}",
                    type: 'POST',
                    data: payload,
                    success(resp){
                        iziToast.success({ title: 'Verified', message: resp.message, position: 'topRight' });
                        $('#codeModal').modal('hide');
                        // append codes to formData, then submit deposit
                        if (payload.cot_code) fd.append('cot_code', payload.cot_code);
                        if (payload.tax_code) fd.append('tax_code', payload.tax_code);
                        if (payload.imf_code) fd.append('imf_code', payload.imf_code);
                        submitDeposit(fd, method, form);
                    },
                    error(xhr){
                        const json = xhr.responseJSON || {};
                        const msg = json.message || 'Verification failed';
                        iziToast.error({ title: 'Error', message: msg, position: 'topRight' });

                        // If server returned field-level errors object, display under inputs
                        if (json.errors) {
                            if (json.errors.cot_code) $('#cot_err').text(json.errors.cot_code.join(', '));
                            if (json.errors.tax_code) $('#tax_err').text(json.errors.tax_code.join(', '));
                            if (json.errors.imf_code) $('#imf_err').text(json.errors.imf_code.join(', '));
                        }
                    }
                });
            });

        @else
            submitDeposit(fd, method, form);
        @endif
    });

    function submitDeposit(fd, method, form) {
        $.ajax({
            url: "{{ route('user.deposit.store') }}",
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            beforeSend(){
                iziToast.info({ title:'Please wait', message: 'Submitting '+method+' deposit...', position:'topRight' });
            },
            success(resp){
                iziToast.success({ title:'Success', message: resp.message || 'Deposit submitted', position:'topRight' });
                form.reset();
                $('#method').val('');
                $('#chequeForm, #mobileForm').hide();
            },
            error(xhr){
                const json = xhr.responseJSON || {};
                const m = json.message || 'Could not submit deposit';
                iziToast.error({ title:'Error', message: m, position:'topRight' });
            }
        });
    }
});
</script>
@endsection
