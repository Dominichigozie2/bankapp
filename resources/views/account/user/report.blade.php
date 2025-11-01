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
                                            <!-- INTERNATIONAL TRANSFER FORM -->
                                            <form id="internationalTransferForm" class="transfer-form p-md-3 active">
                                                @csrf
                                                <h3 class="text-center p-md-4 mb-3">Loan Request</h3>


                                                <div class="mb-3">
                                                    <label class="form-label">Loan Type</label>
                                                    <select name="account_type" class="form-select" required>
                                                        <option value="">Choose Account Type</option>
                                                        <option value="savings">Business Loan</option>
                                                        <option value="current">Individual Loan</option>
                                                        <option value="business">Student Loan</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Account Code</label>
                                                    <input type="text" class="form-control" name="bank_code" placeholder="Enter bank code" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Details</label>
                                                    <textarea class="form-control" name="details" placeholder="Enter transaction details" rows="3"></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">Request Loan</button>
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
            @endsection