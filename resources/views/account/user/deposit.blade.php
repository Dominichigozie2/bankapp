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
                                <option value="">Select Account Type</option>
                                @foreach($accountTypes as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
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
            // 🔹 Toggle between cheque and mobile forms
            $('#method').change(function() {
                var method = $(this).val();
                $('#chequeForm, #mobileForm').hide();
                if (method === 'cheque') $('#chequeForm').show();
                if (method === 'mobile') $('#mobileForm').show();
            });

            // 🔹 Display crypto wallet address
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

            // 🔹 AJAX Submission for both forms
            $('#chequeForm, #mobileForm').on('submit', function(e) {
                e.preventDefault(); // stop normal form submit
                var formData = new FormData(this);
                var method = formData.get('method');

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
                            message: 'Deposit submitted successfully!',
                            position: 'topRight'
                        });
                        e.target.reset();
                        $('#method').val('');
                        $('#chequeForm, #mobileForm').hide();
                    },
                    error: function(xhr) {
                        let errorMessage = 'Something went wrong';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                        }
                        iziToast.error({
                            title: 'Error',
                            message: errorMessage,
                            position: 'topRight'
                        });
                    }
                });
            });
        </script>

        @endsection