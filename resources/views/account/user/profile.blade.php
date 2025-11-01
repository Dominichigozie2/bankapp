@extends('account.user.layout.app')
@section('content')
<div class="page-content">

    <div class="page-content">


        <div class="container-fluid mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <!-- Profile Card -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <!-- Profile Image -->
                            <div class="profile-img mb-3">
                                <img
                                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/users/avatar-3.jpg') }}"
                                    alt="User Image"
                                    class="rounded-circle"
                                    width="100"
                                    height="100">
                            </div>



                            <!-- User Info -->
                            <h4 class="mb-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                            <p class="text-muted mb-2">{{ Auth::user()->email }}</p>

                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="badge bg-success me-2">Verified</span>
                                <small class="text-muted">Joined: {{ Auth::user()->created_at->format('d M, Y') }}</small>
                            </div>

                            <hr>

                            <div class="row text-start px-4">
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted mb-1">Full Name</h6>
                                    <p class="fw-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted mb-1">Email</h6>
                                    <p class="fw-semibold">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted mb-1">Phone</h6>
                                    <p class="fw-semibold">{{ Auth::user()->phone ?? 'Not added' }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted mb-1">Verification Status</h6>
                                    @if(Auth::user()->email_verified_at)
                                    <span class="badge bg-success">Verified</span>
                                    @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Edit Button -->
                            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="bx bx-edit-alt me-1"></i> Edit Profile
                            </button>
                            <button class="btn mt-3" style="background: #a23eff2e;" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                <i class="bx bx-lock me-1"></i> Reset Password
                            </button>
                            <button class="btn mt-3" data-bs-toggle="modal" data-bs-target="#editPasscodeModal" style="background: #a23eff2e;">
                                <i class="bx bx-shield me-1"></i> Edit Passcode
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog centered">
                <form id="editProfileForm" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="editProfileModalLabel"><i class="bx bx-user-circle me-2"></i>Edit Profile</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="avatar" class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit PAssword Modal -->
        <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModal" aria-hidden="true">
            <div class="modal-dialog centered">
                <!-- Change Passcode Modal -->
                <form id="changePasswordForm" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="editProfileModalLabel"><i class="bx bx-user-circle me-2"></i>Edit Password</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Old Password</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Comfirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit PAssword Modal -->
        <div class="modal fade" id="editPasscodeModal" tabindex="-1" aria-labelledby="editPasscodeModal" aria-hidden="true">
            <div class="modal-dialog centered">
                <!-- Change Passcode Modal -->
                <form id="changePasscodeForm" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="editProfileModalLabel"><i class="bx bx-user-circle me-2"></i>Edit Profile</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Old Passcode</label>
                                <input type="text" name="old_passcode" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Passcode</label>
                                <input type="text" name="new_passcode" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Comfirm New Passcode</label>
                                <input type="text" name="new_passcode_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- container-fluid -->
    </div>
</div>
<!-- End Page-content -->

@endsection

@section('scripts')
<script>
    $(function() {
        // helper to show validation errors
        function showValidationErrors(errors) {
            $.each(errors, function(field, msgs) {
                iziToast.error({
                    title: 'Error',
                    message: msgs[0],
                    position: 'topRight'
                });
            });
        }

        // PROFILE (with file)
        $('#editProfileForm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            var url = "{{ route('user.profile.update') }}";

            var formData = new FormData(form);
            formData.set('_token', $('input[name=_token]', form).val());

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        iziToast.success({
                            title: 'Success',
                            message: res.message,
                            position: 'topRight'
                        });
                        $('#editProfileModal').modal('hide');

                        // Refresh page after 2 seconds
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        iziToast.error({
                            title: 'Oops',
                            message: res.message || 'Something went wrong',
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var json = xhr.responseJSON;
                        if (json.errors) {
                            showValidationErrors(json.errors);
                        } else if (json.message) {
                            iziToast.error({
                                title: 'Error',
                                message: json.message,
                                position: 'topRight'
                            });
                        }
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: 'Server error. Try again.',
                            position: 'topRight'
                        });
                    }
                }
            });
        });

        // PASSWORD
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var url = "{{ route('user.password.update') }}";
            var data = $form.serialize();

            $.post(url, data)
                .done(function(res) {
                    if (res.success) {
                        iziToast.success({
                            title: 'Success',
                            message: res.message,
                            position: 'topRight'
                        });
                        $('#editPasswordModal').modal('hide');
                        $form[0].reset();

                        // Refresh after 2 seconds
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: res.message || 'Failed',
                            position: 'topRight'
                        });
                    }
                })
                .fail(function(xhr) {
                    if (xhr.status === 422) {
                        showValidationErrors(xhr.responseJSON.errors || {});
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: 'Server error',
                            position: 'topRight'
                        });
                    }
                });
        });

        // PASSCODE
        $('#changePasscodeForm').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            var url = "{{ route('user.passcode.update') }}";
            var data = $form.serialize();

            $.post(url, data)
                .done(function(res) {
                    if (res.success) {
                        iziToast.success({
                            title: 'Success',
                            message: res.message,
                            position: 'topRight'
                        });
                        $('#editPasscodeModal').modal('hide');
                        $form[0].reset();

                        // Refresh after 2 seconds
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: res.message || 'Failed',
                            position: 'topRight'
                        });
                    }
                })
                .fail(function(xhr) {
                    if (xhr.status === 422) {
                        showValidationErrors(xhr.responseJSON.errors || {});
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: 'Server error',
                            position: 'topRight'
                        });
                    }
                });
        });

    });
</script>

@endsection