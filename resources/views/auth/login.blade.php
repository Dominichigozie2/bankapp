@extends('auth.layout.app')

@section('content')

<div
    class="relative flex items-center justify-center min-h-screen py-12 from-sky-100 dark:from-sky-500/15 ltr:bg-gradient-to-l rtl:bg-gradient-to-r via-green-50 dark:via-green-500/10 to-pink-50 dark:to-pink-500/10">
    <div class="container">
        <div class="grid grid-cols-12">
            <div
                class="col-span-12 mb-0 md:col-span-10 lg:col-span-6 xl:col-span-4 md:col-start-2 lg:col-start-4 xl:col-start-5 card">
                <div class="md:p-10 card-body">
                    <div class="mb-5 text-center">
                        <a href="#">
                            <img src="assets/images/main-logo.png" alt="" class="h-8 mx-auto dark:hidden">
                            <img src="assets/images/logo-white.png" alt="" class="hidden h-8 mx-auto dark:inline-block">
                        </a>
                    </div>

                    <h4
                        class="mb-2 font-bold leading-relaxed text-center text-transparent drop-shadow-lg ltr:bg-gradient-to-r rtl:bg-gradient-to-l from-primary-500 via-purple-500 to-pink-500 bg-clip-text">
                        Login
                    </h4>

                    <p class="mb-5 text-center text-gray-500 dark:text-dark-500">
                        Don't have an account?
                        <a href="register" class="font-medium link link-primary">Sign Up</a>
                    </p>

                    <form id="loginForm">
                        @csrf
                        <div class="grid grid-cols-12 gap-4 mt-5">

                            <!-- Email -->
                            <div class="col-span-12">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" class="form-input w-full" placeholder="Enter your account number">
                            </div>

                            <!-- Password -->
                            <div class="col-span-12">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-input w-full" placeholder="Enter your password">
                            </div>

                            <!-- Passcode -->
                            <!-- <div class="col-span-12">
                                <label class="form-label">Passcode</label>
                                <input type="text" name="passcode" class="form-input w-full" placeholder="Enter your 6-digit passcode">
                            </div> -->

                            <!-- Submit -->
                            <div class="col-span-12">
                                <button type="submit" class="btn btn-primary w-full">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Passcode Modal -->
<div id="createPasscodeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h3 class="text-lg font-bold mb-4">Create Passcode</h3>
        <input type="text" id="newPasscode" class="form-input w-full mb-4" maxlength="6" placeholder="Enter 6-digit passcode">
        <button id="savePasscodeBtn" class="btn btn-primary w-full">Save Passcode</button>
    </div>
</div>

<!-- Verify Passcode Modal -->
<div id="verifyPasscodeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h3 class="text-lg font-bold mb-4">Enter Passcode</h3>
        <input type="text" id="userPasscode" class="form-input w-full mb-4" maxlength="6" placeholder="Enter your passcode">
        <button id="verifyPasscodeBtn" class="btn btn-primary w-full">Verify & Login</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
   $('#loginForm').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: "{{ route('login.post') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(res) {
            if (res.first_time) {
                iziToast.info({
                    title: 'Setup Required',
                    message: res.message,
                    position: 'topRight'
                });
                $('#createPasscodeModal').removeClass('hidden');
                return;
            }

            if (res.require_passcode) {
                iziToast.info({
                    title: 'Verification Needed',
                    message: res.message,
                    position: 'topRight'
                });
                $('#verifyPasscodeModal').removeClass('hidden');
                return;
            }

            if (res.success && res.redirect) {
                iziToast.success({
                    title: 'Success',
                    message: res.message,
                    position: 'topRight'
                });
                setTimeout(() => {
                    window.location.href = res.redirect;
                }, 1200);
            }
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: xhr.responseJSON?.message || 'Login failed.',
                position: 'topRight'
            });
        }
    });
});

// Save new passcode
$('#savePasscodeBtn').on('click', function() {
    const passcode = $('#newPasscode').val();
    const email = $('input[name="email"]').val();

    $.ajax({
        url: "{{ route('user.save.passcode') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            account_number: $('input[name="account_number"]').val(),
            passcode: passcode
        },
        success: function(res) {
            iziToast.success({
                title: 'Success',
                message: res.message,
                position: 'topRight'
            });
            $('#createPasscodeModal').addClass('hidden');
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: xhr.responseJSON?.message || 'Failed to save passcode.',
                position: 'topRight'
            });
        }
    });
});

// Verify passcode and login
// Verify passcode and login
$('#verifyPasscodeBtn').on('click', function() {
    const passcode = $('#userPasscode').val();
    const account_number = $('input[name="account_number"]').val(); // ✅ use account_number

    $.ajax({
        url: "{{ route('user.verify.passcode') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            account_number: account_number,
            passcode: passcode
        },
        success: function(res) {
            iziToast.success({
                title: 'Success',
                message: res.message,
                position: 'topRight'
            });
            $('#verifyPasscodeModal').addClass('hidden');
            if (res.redirect) {
                setTimeout(() => {
                    window.location.href = res.redirect;
                }, 1200);
            }
        },
        error: function(xhr) {
            iziToast.error({
                title: 'Error',
                message: xhr.responseJSON?.message || 'Invalid passcode.',
                position: 'topRight'
            });
        }
    });
});

</script>

@endsection