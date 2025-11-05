        @extends('account.user.layout.app')
        @section('content')
        <div class="page-content">

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

                    {{-- Cheque Deposit Form --}}
                    <form id="chequeForm" enctype="multipart/form-data" style="display:none;">
                        @csrf
                        <input type="hidden" name="method" value="cheque">
                        <div class="mb-3">
                            <label for="cheque_image">Upload Cheque Image</label>
                            <input type="file" name="proof" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Cheque Deposit</button>
                    </form>

                    {{-- Mobile/Crypto Deposit Form --}}
                    <form id="mobileForm" enctype="multipart/form-data" style="display:none;">
                        @csrf
                        <input type="hidden" name="method" value="mobile">

                        <div class="mb-3">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="account_type_id">Account Type</label>
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
                            <label for="crypto_type_id">Crypto Type</label>
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
                            <label for="proof">Upload Proof of Payment</label>
                            <input type="file" name="proof" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Mobile Deposit</button>
                    </form>
                </div>



            </div>


            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @endsection

       @section('scripts')
<script>
$(document).ready(function() {

    // Toggle forms
    $('#method').change(function() {
        var method = $(this).val();
        $('#chequeForm, #mobileForm').hide();
        if (method === 'cheque') $('#chequeForm').show();
        if (method === 'mobile') $('#mobileForm').show();
    });

    // Handle crypto wallet display
    $('#crypto_type_id').change(function() {
        var wallet = $(this).find(':selected').data('wallet');
        if (wallet) {
            $('#networkDiv').show();
            $('#network').val(wallet);
        } else {
            $('#networkDiv').hide();
            $('#network').val('');
        }
    });

    // Show code modal helper
    function showCodeModal(onVerified) {
        if ($('#depositCodeModal').length === 0) {
            $('body').append(`
            <div class="modal fade" id="depositCodeModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Enter Deposit Code</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                            
                        </div>
                        <div class="modal-body">
                        <p class="text-danger" style="fontsize: 10px;">Don't have passcode? <br> Request for the deposit passcode by opening a ticket</p>
                            <div class="mb-3">
                                <input id="deposit_code_input" class="form-control" placeholder="Enter code" />
                            </div>
                            <div id="deposit_code_msg" class="text-danger small"></div>
                            <button id="verify_deposit_code" class="btn btn-primary w-100">Verify Code</button>
                        </div>
                    </div>
                </div>
            </div>`);
        }

        $('#deposit_code_msg').text('');
        $('#deposit_code_input').val('');
        $('#depositCodeModal').modal('show');

        $('#verify_deposit_code').off('click').on('click', function() {
            var code = $('#deposit_code_input').val().trim();
            if (!code) {
                $('#deposit_code_msg').text('Please enter the code.');
                return;
            }

            // ✅ FIXED: Use FormData and proper AJAX to prevent validation errors
            let fd = new FormData();
            fd.append('code', code);
            fd.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('user.deposit.verifyCode') }}",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function(resp) {
                    iziToast.success({ title: 'OK', message: resp.message, position: 'topRight' });
                    $('#depositCodeModal').modal('hide');
                    onVerified(code);
                },
                error: function(xhr) {
                    let msg = (xhr.responseJSON && xhr.responseJSON.message)
                        ? xhr.responseJSON.message
                        : 'Verification failed';
                    $('#deposit_code_msg').text(msg);
                }
            });
        });
    }

    // Handle deposit form submission
    $('#chequeForm, #mobileForm').on('submit', function(e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);
        let method = formData.get('method');

        // Check if deposit code is required
        $.get("{{ route('user.deposit.codeRequired') }}")
            .done(function(resp) {
                if (resp.required) {
                    // require deposit code before submit
                    showCodeModal(function(verifiedCode) {
                        let newFormData = new FormData(form);
                        newFormData.append('verified_code', verifiedCode);
                        submitDepositAjax(newFormData, method, form);
                    });
                } else {
                    submitDepositAjax(formData, method, form);
                }
            })
            .fail(function() {
                iziToast.error({
                    title: 'Error',
                    message: 'Could not check deposit code setting',
                    position: 'topRight'
                });
            });
    });

    // Core AJAX deposit submit
    function submitDepositAjax(formData, method, formElem) {
        $.ajax({
            url: "{{ route('user.deposit.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                iziToast.info({
                    title: 'Please wait...',
                    message: 'Submitting your ' + method + ' deposit...',
                    position: 'topRight'
                });
            },
            success: function(response) {
                iziToast.success({
                    title: 'Success!',
                    message: response.message || 'Deposit submitted successfully!',
                    position: 'topRight'
                });
                formElem.reset();
                $('#method').val('');
                $('#chequeForm, #mobileForm').hide();
            },
            error: function(xhr) {
                let errorMessage = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                iziToast.error({ title: 'Error', message: errorMessage, position: 'topRight' });
            }
        });
    }

});
</script>
@endsection
