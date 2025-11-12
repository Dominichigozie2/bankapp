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
        {{-- Cheque Form --}}
        <form id="chequeForm" enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="hidden" name="method" value="cheque">
            <div class="mb-3">
                <label>Upload Cheque Image</label>
                <input type="file" name="proof" class="form-control cheque-proof" accept="image/*" required>
                <div class="mt-2">
                    <img id="chequePreview" src="" alt="Cheque Preview" style="max-width: 200px; display: none;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Cheque Deposit</button>
        </form>

        {{-- Mobile Form --}}
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
                <select name="user_account_id" class="form-control" required>
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
                <input type="file" name="proof" class="form-control mobile-proof" accept="image/*" required>
                <div class="mt-2">
                    <img id="mobilePreview" src="" alt="Mobile Deposit Preview" style="max-width: 200px; display: none;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit Mobile Deposit</button>
        </form>
    </div>
</div>

{{-- Instruction Modal (shows when any code enabled) --}}
{{-- Instruction Modal --}}
@if($settings && ($settings->cot_enabled || $settings->tax_enabled || $settings->imf_enabled))
<div class="modal fade" id="instructionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Important</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {!! $settings->deposit_instruction ?? 'Please request for your deposit codes before proceeding.' !!}
            </div>
            <div class="modal-footer">
                <a href="account/report" class="btn btn-primary">Request Code</a>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- COT Modal --}}
@if($settings->cot_enabled)
<div class="modal fade" id="cotModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Enter COT Code</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{!! $settings->cot_dep_message ?? 'Please request for your transaction codes before proceeding with any transfer.' !!}
                    <input type="text" id="cot_code" class="form-control" placeholder="Enter COT code">
                    <small id="cot_err" class="text-danger d-block"></small>
                    <button id="verifyCot" class="btn btn-primary w-100 mt-2">Verify COT</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- TAX Modal --}}
@if($settings->tax_enabled)
<div class="modal fade" id="taxModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Enter TAX Code</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{!! $settings->tax_dep_message ?? 'Please request for your transaction codes before proceeding with any transfer.' !!}

                    <input type="text" id="tax_code" class="form-control" placeholder="Enter TAX code">
                    <small id="tax_err" class="text-danger d-block"></small>
                    <button id="verifyTax" class="btn btn-primary w-100 mt-2">Verify TAX</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- IMF Modal --}}
@if($settings->imf_enabled)
<div class="modal fade" id="imfModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Enter IMF Code</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{!! $settings->imf_dep_message ?? 'Please request for your transaction codes before proceeding with any transfer.' !!}
                    <input type="text" id="imf_code" class="form-control" placeholder="Enter IMF code">
                    <small id="imf_err" class="text-danger d-block"></small>
                    <button id="verifyImf" class="btn btn-primary w-100 mt-2">Verify IMF</button>
            </div>
        </div>
    </div>
</div>
@endif



@endsection

@section('scripts')
<script>
    $(function() {
        // Show instruction modal if any code is enabled
        @if($settings && ($settings->cot_enabled || $settings->tax_enabled || $settings->imf_enabled))
        new bootstrap.Modal(document.getElementById('instructionModal')).show();
        @endif

        // Toggle between cheque and mobile forms
        $('#method').change(function() {
            $('#chequeForm, #mobileForm').hide();
            const v = $(this).val();
            if (v === 'cheque') $('#chequeForm').show();
            if (v === 'mobile') $('#mobileForm').show();
        });

        // Display crypto wallet network (if any)
        $('#crypto_type_id').change(function() {
            const w = $(this).find(':selected').data('wallet');
            if (w) {
                $('#networkDiv').show();
                $('#network').val(w);
            } else {
                $('#networkDiv').hide();
                $('#network').val('');
            }
        });

        // Handle deposit form submit (cheque or mobile)
        $('#chequeForm, #mobileForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const fd = new FormData(form);
            const method = fd.get('method');
            let verifiedCodes = {};

            // Determine enabled verification sequence
            const enabled = [];
            @if($settings->cot_enabled) enabled.push('cot');
            @endif
            @if($settings->tax_enabled) enabled.push('tax');
            @endif
            @if($settings->imf_enabled) enabled.push('imf');
            @endif

            // Run sequential modals
            function openNext(index) {
                if (index >= enabled.length) {
                    // All codes verified → attach them and submit
                    for (const k in verifiedCodes) fd.append(k + '_code', verifiedCodes[k]);
                    submitDeposit(fd, method, form);
                    return;
                }

                const type = enabled[index];
                let modalId = '',
                    inputId = '',
                    errorId = '',
                    buttonId = '';
                if (type === 'cot') {
                    modalId = '#cotModal';
                    inputId = '#cot_code';
                    errorId = '#cot_err';
                    buttonId = '#verifyCot';
                }
                if (type === 'tax') {
                    modalId = '#taxModal';
                    inputId = '#tax_code';
                    errorId = '#tax_err';
                    buttonId = '#verifyTax';
                }
                if (type === 'imf') {
                    modalId = '#imfModal';
                    inputId = '#imf_code';
                    errorId = '#imf_err';
                    buttonId = '#verifyImf';
                }

                const modal = new bootstrap.Modal(document.getElementById(modalId.substring(1)));
                modal.show();
                $(inputId).val('').focus();
                $(errorId).text('');

                // Verify button click
                $(buttonId).off('click').on('click', function() {
                    const code = $(inputId).val().trim();
                    if (!code) {
                        $(errorId).text('Code is required');
                        return;
                    }

                    $.post("{{ route('user.deposit.verifySingleCode') }}", {
                        _token: '{{ csrf_token() }}',
                        code_type: type,
                        code: code
                    }, function(res) {
                        if (res.success) {
                            verifiedCodes[type] = code;
                            modal.hide();
                            setTimeout(() => openNext(index + 1), 200);
                        } else {
                            $(errorId).text(res.message || 'Invalid code');
                        }
                    }).fail(function(xhr) {
                        $(errorId).text(xhr.responseJSON?.message || 'Verification failed');
                    });
                });

                // Also trigger verification on Enter key
                $(inputId).off('keypress').on('keypress', function(e) {
                    if (e.which === 13) {
                        $(buttonId).trigger('click');
                        return false;
                    }
                });
            }

            if (enabled.length === 0) {
                // no verification required
                submitDeposit(fd, method, form);
            } else {
                openNext(0);
            }
        });

        // Function: actually submit deposit via AJAX
        function submitDeposit(fd, method, form) {
            $.ajax({
                url: "{{ route('user.deposit.store') }}",
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                beforeSend() {
                    iziToast.info({
                        title: 'Please wait',
                        message: 'Submitting ' + method + ' deposit...',
                        position: 'topRight'
                    });
                },
                success(resp) {
                    iziToast.success({
                        title: 'Success',
                        message: resp.message || 'Deposit submitted',
                        position: 'topRight'
                    });
                    form.reset();
                    $('#method').val('');
                    $('#chequeForm, #mobileForm').hide();
                },
                error(xhr) {
                    iziToast.error({
                        title: 'Error',
                        message: xhr.responseJSON?.message || 'Deposit failed',
                        position: 'topRight'
                    });
                }
            });
        }
    });

    // Image previews
    function previewImage(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    // Cheque and Mobile proof previews
    document.querySelector('.cheque-proof').addEventListener('change', function() {
        previewImage(this, 'chequePreview');
    });
    document.querySelector('.mobile-proof').addEventListener('change', function() {
        previewImage(this, 'mobilePreview');
    });
</script>
@endsection