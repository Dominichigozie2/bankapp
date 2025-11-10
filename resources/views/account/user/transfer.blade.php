@extends('account.user.layout.app')
@section('content')

<style>
    .transfer-form {
        display: none;
    }

    .transfer-form.active {
        display: block;
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Transfer Instruction Modal -->
@if($settings && ($settings->cot_enabled || $settings->tax_enabled || $settings->imf_enabled))
<div class="modal fade" id="instructionModal" tabindex="-1" aria-labelledby="instructionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="instructionModalLabel">Important Notice</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {!! $settings->tax_message ?? 'Please request for your transaction codes before proceeding with any transfer.' !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Got It</button>
                <a href="/account/report" class="btn btn-primary">Request Code</a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="page-content">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="card-title mb-4 text-center">Transfer Funds</h4>

                        <!-- Transfer Type Selection -->
                        <div class="mb-4 text-center">
                            <label class="form-label fw-semibold">Select Transfer Type</label>
                            <select id="transferType" class="form-select w-50 mx-auto">
                                <option value="">-- Choose Transfer Type --</option>
                                <option value="local">Local Transfer</option>
                                <option value="international">International Transfer</option>
                                <option value="self">Self Transfer</option>
                            </select>
                        </div>

                        <!-- LOCAL TRANSFER FORM -->
                        <form id="localTransferForm" class="transfer-form">
                            @csrf
                            <h5 class="mb-3">Local Transfer</h5>

                            <div class="mb-3">
                                <label class="form-label">Amount (Total Balance: ${{ number_format($user->balance ?? 0, 2) }})</label>
                                <input type="number" class="form-control" name="amount" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Account</label>
                                <select name="account" class="form-select" required>
                                    <option value="">Select Source Account</option>
                                    @foreach($userAccounts as $acct)
                                    <option value="{{ $acct->id }}" {{ $acct->is_active ? 'selected' : '' }}>
                                        {{ $acct->accountType->name }} {{ $acct->account_number ? '- '.$acct->account_number : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control" name="account_number" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Account Name</label>
                                <input type="text" class="form-control" name="account_name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Account PIN (Passcode)</label>
                                <input type="password" class="form-control" name="passcode" minlength="6" maxlength="6" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Details</label>
                                <textarea class="form-control" name="details" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Proceed Local Transfer</button>
                        </form>

                        <!-- INTERNATIONAL TRANSFER FORM -->
                        <form id="internationalTransferForm" class="transfer-form">
                            @csrf
                            <h5 class="mb-3">International Transfer</h5>

                            <div class="mb-3">
                                <label class="form-label">Amount (Total Balance: ${{ number_format($user->balance ?? 0, 2) }})</label>
                                <input type="number" class="form-control" name="amount" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Account</label>
                                <select name="account" class="form-select" required>
                                    <option value="">Select Source Account</option>
                                    @foreach($userAccounts as $acct)
                                    <option value="{{ $acct->id }}" {{ $acct->is_active ? 'selected' : '' }}>
                                        {{ $acct->accountType->name }} {{ $acct->account_number ? '- '.$acct->account_number : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Account Number</label>
                                <input type="text" class="form-control" name="account_number" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Account Name</label>
                                <input type="text" class="form-control" name="account_name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bank Country</label>
                                <input type="text" class="form-control" name="bank_country" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Routine Number</label>
                                <input type="text" class="form-control" name="routine_number" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bank Code</label>
                                <input type="text" class="form-control" name="bank_code" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Details</label>
                                <textarea class="form-control" name="details" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Proceed International Transfer</button>
                        </form>

                        <!-- SELF TRANSFER FORM -->
                        <form id="selfTransferForm" class="transfer-form">
                            @csrf
                            <h5 class="mb-3">Self Transfer</h5>

                            <div class="mb-3">
                                <label class="form-label">Amount (Total Balance: ${{ number_format($user->balance ?? 0, 2) }})</label>
                                <input type="number" class="form-control" name="amount" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">From Account</label>
                                <select name="from_account" class="form-select" required>
                                    <option value="">Select Source Account</option>
                                    @foreach($userAccounts as $acct)
                                    <option value="{{ $acct->id }}" {{ $acct->is_active ? 'selected' : '' }}>
                                        {{ $acct->accountType->name }} {{ $acct->account_number ? '- '.$acct->account_number : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">To Account</label>
                                <select name="to_account" class="form-select" required>
                                    <option value="">Select Destination Account</option>
                                    @foreach($userAccounts as $acct)
                                    <option value="{{ $acct->id }}">
                                        {{ $acct->accountType->name }} {{ $acct->account_number ? '- '.$acct->account_number : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Account PIN (Passcode)</label>
                                <input type="password" class="form-control" name="passcode" minlength="6" maxlength="6" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Proceed Self Transfer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Passcode Modal (Local & Self Transfers) -->
<div class="modal fade" id="passcodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="passcodeForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enter Account Passcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="password" name="passcode" id="passcodeInput" class="form-control" placeholder="6-digit passcode" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>



{{-- Codes Verification Modal --}}
<div class="modal fade" id="codeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Enter Required Codes</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">{!! $settings->deposit_instruction ?? 'Provide the codes required for this transfer.' !!}</p>

                @if($settings->cot_enabled)
                <div class="mb-3">
                    <label>COT Code</label>
                    <input type="text" id="cot_code" class="form-control" placeholder="Enter your COT Code">
                    <small id="cot_err" class="text-danger d-block"></small>
                </div>
                @endif

                @if($settings->tax_enabled)
                <div class="mb-3">
                    <label>TAX Code</label>
                    <input type="text" id="tax_code" class="form-control" placeholder="Enter your TAX Code">
                    <small id="tax_err" class="text-danger d-block"></small>
                </div>
                @endif

                @if($settings->imf_enabled)
                <div class="mb-3">
                    <label>IMF Code</label>
                    <input type="text" id="imf_code" class="form-control" placeholder="Enter your IMF Code">
                    <small id="imf_err" class="text-danger d-block"></small>
                </div>
                @endif

                <button id="verifyAllCodes" class="btn btn-primary w-100 mt-2">Verify Codes</button>
            </div>
        </div>
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


@endsection

@section('scripts')

<script>
 // show instruction modal if any code enabled
 @if($settings && ($settings->cot_enabled || $settings->tax_enabled || $settings->imf_enabled))
    var insModal = new bootstrap.Modal(document.getElementById('instructionModal'));
    insModal.show();
 @endif

document.getElementById('transferType').addEventListener('change', function() {
    const forms = ['localTransferForm', 'internationalTransferForm', 'selfTransferForm'];
    forms.forEach(id => document.getElementById(id).classList.remove('active'));
    if(this.value === 'local') document.getElementById('localTransferForm').classList.add('active');
    if(this.value === 'international') document.getElementById('internationalTransferForm').classList.add('active');
    if(this.value === 'self') document.getElementById('selfTransferForm').classList.add('active');
});

// Function: Submit form via AJAX
function submitFormAJAX(form, url, redirect=true){
    $.ajax({
        url: url,
        type: 'POST',
        data: form.serialize()+"&_token={{ csrf_token() }}",
        success: function(res){
            if(res.success){
                iziToast.success({title:'Success', message:res.message});
                if(redirect && res.redirect) {
                    setTimeout(()=> window.location.href = res.redirect, 1200);
                }
            } else {
                iziToast.error({title:'Error', message:res.message || 'Transfer failed'});
            }
        },
        error: function(xhr){
            let msg = 'Something went wrong';
            if(xhr.responseJSON){
                msg = xhr.responseJSON.message || msg;
                if(xhr.responseJSON.errors){
                    for(const key in xhr.responseJSON.errors){
                        $('#' + key + '_err').text(xhr.responseJSON.errors[key]);
                    }
                }
            }
            iziToast.error({title:'Error', message: msg});
        }
    });
}

// LOCAL & SELF TRANSFERS: Show passcode modal
$('#localTransferForm, #selfTransferForm').on('submit', function(e){
    e.preventDefault();
    const form = $(this);
    $('#passcodeModal').modal('show');

    $('#passcodeForm').off('submit').on('submit', function(e){
        e.preventDefault();
        const passcode = $('#passcodeInput').val();
        form.find('input[name="passcode"]').val(passcode);
        $('#passcodeModal').modal('hide');

        // Determine which URL to use
        let url = '';
        if(form.attr('id') === 'localTransferForm') url = "{{ route('user.transfer.local') }}";
        if(form.attr('id') === 'selfTransferForm') url = "{{ route('user.transfer.self') }}";

        submitFormAJAX(form, url);
    });
});

// INTERNATIONAL TRANSFER: Show codes modal if any enabled

</script>

@endsection
