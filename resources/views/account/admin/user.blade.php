        @extends('account.admin.layout.apps')
        @section('content')

        <div class="page-content">
            <div class="container-fluid">

                <div class="card row">
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Manage Users</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="bi bi-plus-circle"></i> Add User
                            </button>
                        </div>
                        <div class="row flex justify-content-end">

                            {{-- Search Form --}}
                            <form method="GET" action="{{ route('admin.user.index') }}" class="mb-3 col-md-3 ">
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search users by name or email...">
                                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                                </div>
                            </form>

                        </div>
                        {{-- Users Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Verified</th>
                                        <th>Balance</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $user)
                                    <tr id="user-row-{{ $user->id }}">
                                        <td>{{ $users->firstItem() + $index }}</td>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>
                                            @if($user->verified)
                                            <span class="badge bg-success">Verified</span>
                                            @else
                                            <span class="badge bg-warning">Unverified</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($user->balance, 2) }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm viewUser" data-id="{{ $user->id }}"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-success btn-sm verifyUser" data-id="{{ $user->id }}"><i class="bi bi-check-circle"></i></button>
                                            <button class="btn btn-danger btn-sm deleteUser" data-id="{{ $user->id }}"><i class="bi bi-trash"></i></button>
                                            @if($user->banned)
                                            <button class="btn btn-secondary btn-sm banUser" data-id="{{ $user->id }}">
                                                <i class="bi bi-unlock"></i>
                                            </button>
                                            @else
                                            <button class="btn btn-warning btn-sm banUser" data-id="{{ $user->id }}">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                            @endif

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">No users found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    </div>

                    {{-- Add User Modal --}}
                    <div class="modal fade" id="addUserModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="addUserForm">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label>First Name</label>
                                            <input type="text" name="first_name" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Last Name</label>
                                            <input type="text" name="last_name" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control">
                                        </div>
                                        <div class="mb-2">
                                            <label>Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Role</label>
                                            <select name="role" class="form-select">
                                                <option value="user">User</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label>Account Type</label>
                                            <select name="account_type_id" class="form-select">
                                                <option value="">Select Account Type</option>
                                                @foreach(\App\Models\AccountType::all() as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label>Currency</label>
                                            <select name="currency_id" class="form-select">
                                                <option value="">Select Currency</option>
                                                @foreach(\App\Models\Currency::all() as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Add User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    {{-- View User Modal --}}
                    <div class="modal fade" id="viewUserModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">User Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" id="userDetails">
                                    <p>Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

            </div>
        </div>
        @endsection


        @section('scripts')
        <script>
            $(function() {
                // Add User
                $('#addUserForm').on('submit', function(e) {
                    e.preventDefault();

                    // Grab all fields
                    let data = {
                        _token: "{{ csrf_token() }}",
                        first_name: $('input[name="first_name"]').val(),
                        last_name: $('input[name="last_name"]').val(),
                        email: $('input[name="email"]').val(),
                        phone: $('input[name="phone"]').val(),
                        password: $('input[name="password"]').val(),
                        passcode: $('input[name="passcode"]').val(),
                        role: $('select[name="role"]').val(),
                        account_type_id: $('select[name="account_type_id"]').val(),
                        currency_id: $('select[name="currency_id"]').val(),
                    };

                    $.ajax({
                        url: "{{ route('admin.user.store') }}",
                        type: "POST",
                        data: data,
                        success: function(res) {
                            iziToast.success({
                                title: 'Success',
                                message: res.message
                            });
                            $('#addUserModal').modal('hide');
                            setTimeout(() => location.reload(), 1000);
                        },
                        error: function(xhr) {
                            let msg = xhr.responseJSON?.message || 'Failed to add user';
                            iziToast.error({
                                title: 'Error',
                                message: msg
                            });
                        }
                    });
                });





                // Use delegated binding for dynamic buttons
                $(document).on('click', '.viewUser', function() {
                    const id = $(this).data('id');
                    $.get("{{ url('admin/user/view') }}/" + id, function(user) {
                        $('#userDetails').html(`
                <ul class="list-group">
                    <li class="list-group-item"><strong>Name:</strong> ${user.first_name} ${user.last_name}</li>
                    <li class="list-group-item"><strong>Email:</strong> ${user.email}</li>
                    <li class="list-group-item"><strong>Phone:</strong> ${user.phone ?? 'N/A'}</li>
                    <li class="list-group-item"><strong>Balance:</strong> $${user.balance}</li>
                    <li class="list-group-item"><strong>Account Number:</strong> ${user.current_account_number}</li>
                    <li class="list-group-item"><strong>User Password:</strong> ${user.plain_password}</li>
                    <li class="list-group-item"><strong>Role:</strong> ${user.role}</li>
                    <li class="list-group-item"><strong>Verified:</strong> ${user.verified ? 'Yes' : 'No'}</li>
                    <li class="list-group-item"><strong>Joined:</strong> ${user.created_at}</li>
                </ul>
            `);
                        $('#viewUserModal').modal('show');
                    });
                });

                $(document).on('click', '.verifyUser', function() {
                    const id = $(this).data('id');
                    $.post("{{ url('admin/user/verify') }}/" + id, {
                        _token: "{{ csrf_token() }}"
                    }, function(res) {
                        if (res.success) {
                            iziToast.success({
                                title: 'Updated',
                                message: res.status ? 'User verified' : 'User unverified'
                            });
                            setTimeout(() => location.reload(), 800);
                        }
                    });
                });

                $(document).on('click', '.deleteUser', function() {
                    const id = $(this).data('id');
                    if (confirm('Are you sure you want to delete this user?')) {
                        $.ajax({
                            url: "{{ url('admin/user/delete') }}/" + id,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                if (res.success) {
                                    $('#user-row-' + id).remove();
                                    iziToast.success({
                                        title: 'Deleted',
                                        message: 'User removed successfully'
                                    });
                                }
                            }
                        });
                    }
                });

                $(document).on('click', '.banUser', function() {
                    const id = $(this).data('id');

                    $.post("{{ url('admin/user/ban') }}/" + id, {
                        _token: "{{ csrf_token() }}"
                    }, function(res) {
                        if (res.success) {
                            iziToast.warning({
                                title: 'User Status Changed',
                                message: res.message
                            });
                            setTimeout(() => location.reload(), 800);
                        }
                    }).fail(function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message: xhr.responseJSON?.message || 'Something went wrong.'
                        });
                    });
                });

            });
        </script>
        @endsection