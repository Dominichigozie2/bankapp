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
                                            <form id="ticketForm" class="transfer-form p-md-3 active">
                                                @csrf
                                                <h3 class="text-center p-md-4 mb-3">Ticket</h3>

                                                <div class="mb-3">
                                                    <label class="form-label">Ticket Type</label>
                                                    <select name="account_type" class="form-select" required>
                                                        <option value="">Choose Ticket Type</option>
                                                        <option value="MyAccount">My Account</option>
                                                        <option value="Transfer">Transfer</option>
                                                        <option value="Security">Security</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Ticket Message</label>
                                                    <textarea class="form-control" name="details" placeholder="Enter details" rows="3"></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Account PIN</label>
                                                    <input type="password" class="form-control" name="passcode" placeholder="Enter account PIN" required>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100">Send Ticket</button>
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
                $(document).ready(function() {

                    // Handle ticket submission
                    $('#ticketForm').on('submit', function(e) {
                        e.preventDefault();

                        let form = $(this);
                        let formData = form.serialize(); // grabs all inputs

                        $.ajax({
                            url: "{{ route('user.tickets.store') }}", // Laravel route
                            method: "POST",
                            data: formData,
                            beforeSend: function() {
                                iziToast.info({
                                    title: 'Please wait',
                                    message: 'Submitting your ticket...',
                                    position: 'topRight'
                                });
                            },
                            success: function(response) {
                                iziToast.success({
                                    title: 'Success',
                                    message: response.message,
                                    position: 'topRight'
                                });

                                form.trigger('reset'); // clear inputs
                            },
                            error: function(xhr) {
                                // Laravel validation or custom error
                                if (xhr.responseJSON && xhr.responseJSON.errors) {
                                    $.each(xhr.responseJSON.errors, function(key, value) {
                                        iziToast.error({
                                            title: 'Error',
                                            message: value[0],
                                            position: 'topRight'
                                        });
                                    });
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    iziToast.error({
                                        title: 'Error',
                                        message: xhr.responseJSON.message,
                                        position: 'topRight'
                                    });
                                } else {
                                    iziToast.error({
                                        title: 'Error',
                                        message: 'Something went wrong!',
                                        position: 'topRight'
                                    });
                                }
                            }
                        });
                    });
                });
            </script>
            @endsection