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
            @extends('account.user.layout.app')
            @section('content')
            <div class="page-content">

                <div class="page-content">
                    <div class="container mt-5">
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
                                                </select>
                                            </div>

                                            <!-- LOCAL TRANSFER FORM -->
                                            <form id="localTransferForm" class="transfer-form">
                                                @csrf
                                                <h5 class="mb-3">Local Transfer</h5>

                                                <div class="mb-3">
                                                    <label class="form-label">Amount (Total Balance: ${{ number_format(Auth::user()->balance ?? 0, 2) }})</label>
                                                    <input type="number" class="form-control" name="amount" placeholder="Enter amount" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Payment Account</label>
                                                    <input type="text" class="form-control" value="{{ Auth::user()->account_type->name ?? 'Savings' }}" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Bank Name</label>
                                                    <input type="text" class="form-control" name="bank_name" placeholder="Enter bank name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Account Number</label>
                                                    <input type="text" class="form-control" name="account_number" placeholder="Enter account number" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Account Name</label>
                                                    <input type="text" class="form-control" name="account_name" placeholder="Enter account name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Details</label>
                                                    <textarea class="form-control" name="details" placeholder="Enter transaction details" rows="3"></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">Proceed Local Transfer</button>
                                            </form>

                                            <!-- INTERNATIONAL TRANSFER FORM -->
                                            <form id="internationalTransferForm" class="transfer-form">
                                                @csrf
                                                <h5 class="mb-3">International Transfer</h5>

                                                <div class="mb-3">
                                                    <label class="form-label">Amount (Total Balance: ${{ number_format(Auth::user()->balance ?? 0, 2) }})</label>
                                                    <input type="number" class="form-control" name="amount" placeholder="Enter amount" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Payment Account</label>
                                                    <input type="text" class="form-control" value="{{ Auth::user()->account_type->name ?? 'Savings' }}" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Account Type</label>
                                                    <select name="account_type" class="form-select" required>
                                                        <option value="">Choose Account Type</option>
                                                        <option value="savings">Savings Account</option>
                                                        <option value="current">Current Account</option>
                                                        <option value="business">Business Account</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Bank Name</label>
                                                    <input type="text" class="form-control" name="bank_name" placeholder="Enter bank name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Account Name</label>
                                                    <input type="text" class="form-control" name="account_name" placeholder="Enter account name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Account Number</label>
                                                    <input type="text" class="form-control" name="account_number" placeholder="Enter account number" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Bank Country</label>
                                                    <input type="text" class="form-control" name="bank_country" placeholder="Enter bank country" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Routine Number</label>
                                                    <input type="text" class="form-control" name="routine_number" placeholder="Enter routine number" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Bank Code</label>
                                                    <input type="text" class="form-control" name="bank_code" placeholder="Enter bank code" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Details</label>
                                                    <textarea class="form-control" name="details" placeholder="Enter transaction details" rows="3"></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">Proceed International Transfer</button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>


                    </div>


                    <!-- container-fluid -->
                </div>
            </div>
            <!-- End Page-content -->

            @endsection
            @section('scripts')
            <script>
                document.getElementById('transferType').addEventListener('change', function() {
                    const localForm = document.getElementById('localTransferForm');
                    const intlForm = document.getElementById('internationalTransferForm');
                    if (this.value === 'local') {
                        localForm.classList.add('active');
                        intlForm.classList.remove('active');
                    } else if (this.value === 'international') {
                        intlForm.classList.add('active');
                        localForm.classList.remove('active');
                    } else {
                        localForm.classList.remove('active');
                        intlForm.classList.remove('active');
                    }
                });
            </script>

            @endsection