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
            @php
            $settings = $settings ?? \App\Models\AdminSetting::first();
            @endphp

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

            <!-- CODE & PASSCODE MODALS (place inside transfer.blade.php) -->
            <!-- Modal for collecting codes (COT/TAX/IMF) -->
            <div class="modal fade" id="codesModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="codesForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Additional Verification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="codesContainer"></div>
                                <div class="mt-2">
                                    <small>If you don't have the required code(s), please create a ticket with support.</small>
                                    <div class="mt-2">
                                        <a href="report" class="btn btn-link">Request Code via Ticket</a>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Continue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PASSCODE modal -->
            <div class="modal fade" id="passcodeModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="passcodeForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Enter Account Code (Passcode)</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Account Code</label>
                                    <input type="password" name="passcode" class="form-control" required minlength="6" maxlength="6" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Verify & Submit</button>
                            </div>
                        </form>
                    </div>
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

                $(function() {
                    // store transfer payload between modals
                    let transferPayload = null;
                    let transferType = null;
                    let requiredCodes = []; // e.g. ['cot','tax']

                    // helper to open codes modal with input fields
                    function openCodesModal(settings) {
                        const container = $('#codesContainer');
                        container.empty();
                        requiredCodes = [];
                        if (settings.cot_enabled) {
                            container.append(`<div class="mb-2"><label>COT Code</label><input type="text" name="cot_code" class="form-control" required></div>`);
                            requiredCodes.push('cot_code');
                        }
                        if (settings.tax_enabled) {
                            container.append(`<div class="mb-2"><label>TAX Code</label><input type="text" name="tax_code" class="form-control" required></div>`);
                            requiredCodes.push('tax_code');
                        }
                        if (settings.imf_enabled) {
                            container.append(`<div class="mb-2"><label>IMF Code</label><input type="text" name="imf_code" class="form-control" required></div>`);
                            requiredCodes.push('imf_code');
                        }
                        $('#codesModal').modal('show');
                    }

                    // Local transfer submit
                    $('#localTransferForm').on('submit', function(e) {
                        e.preventDefault();
                        transferType = 'local';
                        transferPayload = $(this).serializeArray().reduce((o, i) => (o[i.name] = i.value, o), {});
                        // show passcode modal directly
                        $('#passcodeModal').modal('show');
                    });

                    // International submit
                    $('#internationalTransferForm').on('submit', function(e) {
                        e.preventDefault();
                        transferType = 'international';
                        transferPayload = $(this).serializeArray().reduce((o, i) => (o[i.name] = i.value, o), {});
                        // check settings passed from server (blade provides window.transferSettings)
                        if (window.transferSettings) {
                            const s = window.transferSettings;
                            if (!s.transfers_enabled) {
                                iziToast.error({
                                    title: 'Restricted',
                                    message: 'Transfers are disabled. Contact support.'
                                });
                                return;
                            }
                            if (s.cot_enabled || s.tax_enabled || s.imf_enabled) {
                                openCodesModal(s);
                            } else {
                                // no extra codes required, go to passcode step
                                $('#passcodeModal').modal('show');
                            }
                        } else {
                            // fallback: open passcode
                            $('#passcodeModal').modal('show');
                        }
                    });

                    // when codes modal submitted -> close and open passcode modal
                    $('#codesForm').on('submit', function(e) {
                        e.preventDefault();
                        const codes = $(this).serializeArray().reduce((o, i) => (o[i.name] = i.value, o), {});
                        // merge codes into payload
                        transferPayload = {
                            ...transferPayload,
                            ...codes
                        };
                        $('#codesModal').modal('hide');
                        setTimeout(() => $('#passcodeModal').modal('show'), 250);
                    });

                    // passcode modal final submit -> call server
                    $('#passcodeForm').on('submit', function(e) {
                        e.preventDefault();
                        const passcode = $(this).find('[name="passcode"]').val();
                        transferPayload = {
                            ...transferPayload,
                            passcode
                        };

                        // decide endpoint
                        const url = (transferType === 'local') ? "{{ route('user.transfer.local') }}" : "{{ route('user.transfer.international') }}";

                        $.ajax({
                            url,
                            type: 'POST',
                            data: {
                                ...transferPayload,
                                _token: "{{ csrf_token() }}"
                            },
                            success(res) {
                                if (res.success) {
                                    iziToast.success({
                                        title: 'Success',
                                        message: res.message
                                    });
                                    $('#passcodeModal').modal('hide');
                                    setTimeout(() => {
                                        if (res.redirect) window.location.href = res.redirect;
                                        else location.reload();
                                    }, 1000);
                                } else {
                                    iziToast.error({
                                        title: 'Error',
                                        message: res.message || 'Transfer failed'
                                    });
                                }
                            },
                            error(xhr) {
                                const msg = xhr.responseJSON?.message || 'Error occurred';
                                iziToast.error({
                                    title: 'Error',
                                    message: msg
                                });
                            }
                        });
                    });

                    // Provide settings to client-side (from Blade: $settings)
                    window.transferSettings = {
                        cot_enabled: {{ $settings->cot_enabled ? 'true' : 'false' }},
                        tax_enabled: {{ $settings->tax_enabled ? 'true' : 'false' }},
                        imf_enabled: {{ $settings->imf_enabled ? 'true' : 'false' }},
                        transfers_enabled: {{ $settings->transfers_enabled ? 'true' : 'false' }}
                    };
                });
            </script>

            @endsection