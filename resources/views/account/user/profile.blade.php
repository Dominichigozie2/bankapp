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
                        <img src="{{ asset('assets/images/users/avatar-3.jpg') }}" alt="User Image" class="rounded-circle" width="100" height="100">
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
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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


        <!-- container-fluid -->
    </div>
</div>
<!-- End Page-content -->

@endsection
@section('scripts')
@endsection