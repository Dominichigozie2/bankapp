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
                        Create a New Account
                    </h4>

                    <p class="mb-5 text-center text-gray-500 dark:text-dark-500">
                        Already have an account?
                        <a href="login" class="font-medium link link-primary">Sign In</a>
                    </p>

                    <form id="registerForm">
                        @csrf
                        <div class="grid grid-cols-12 gap-4 mt-5">

                            <!-- First Name -->
                            <div class="col-span-12 md:col-span-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input id="firstName" name="first_name" class="w-full form-input"
                                    placeholder="Enter your first name">
                            </div>

                            <!-- Last Name -->
                            <div class="col-span-12 md:col-span-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input id="lastName" name="last_name" class="w-full form-input"
                                    placeholder="Enter your last name">
                            </div>

                            <!-- Email -->
                            <div class="col-span-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="w-full form-input"
                                    placeholder="Enter your email">
                            </div>

                            <!-- Phone Number -->
                            <div class="col-span-12">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="w-full form-input"
                                    placeholder="Enter your phone number">
                            </div>

                            <!-- Account Type -->
                            <div class="col-span-12">
                                <label for="accountType" class="form-label">Account Type</label>
                                <select id="accountType" name="account_type_id" class="w-full form-select">
                                    <option value="">Select Account Type</option>
                                    @foreach ($accountTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Currency -->
                            <div class="col-span-12">
                                <label for="currency" class="form-label">Currency</label>
                                <select id="currency" name="currency_id" class="w-full form-select">
                                    <option value="">Select Currency</option>
                                    @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Password -->
                            <div class="col-span-12">
                                <label for="password" class="form-label">Password</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password"
                                        class="form-input ltr:pr-8 rtl:pl-8" placeholder="Enter your password">
                                    <button type="button" onclick="togglePassword('password')"
                                        class="absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-500 ltr:right-3 rtl:left-3 focus:outline-none">
                                        <i data-lucide="eye-off" class="size-5"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-span-12">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" id="confirm_password" name="password_confirmation"
                                        class="form-input ltr:pr-8 rtl:pl-8" placeholder="Confirm your password">
                                    <button type="button" onclick="togglePassword('confirm_password')"
                                        class="absolute inset-y-0 flex items-center text-gray-500 dark:text-dark-500 ltr:right-3 rtl:left-3 focus:outline-none">
                                        <i data-lucide="eye-off" class="size-5"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-span-12">
                                <button type="submit" class="btn btn-primary w-full">Register</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye-off');
        }
        lucide.createIcons();
    }

    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('register.post') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                iziToast.success({
                    title: 'Success',
                    message: res.message,
                    position: 'topRight'
                });

                setTimeout(() => {
                    $('#registerForm')[0].reset();
                }, 1000);
            },

            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    iziToast.error({
                        title: 'Error',
                        message: value[0],
                        position: 'topRight'
                    });
                });
            }
        });
    });
</script>
@endsection