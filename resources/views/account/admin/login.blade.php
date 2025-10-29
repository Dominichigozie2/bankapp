@extends('auth.layout.app')

@section('content')

<div class="relative flex items-center justify-center min-h-screen py-12 from-sky-100 dark:from-sky-500/15 ltr:bg-gradient-to-l rtl:bg-gradient-to-r via-green-50 dark:via-green-500/10 to-pink-50 dark:to-pink-500/10">
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
                        Admin Login
                    </h4>

                    <form id="adminLoginForm">
                        @csrf
                        <div class="grid grid-cols-12 gap-4 mt-5">

                            <!-- Email -->
                            <div class="col-span-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-input w-full" placeholder="Enter your email">
                            </div>

                            <!-- Password -->
                            <div class="col-span-12">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-input w-full" placeholder="Enter your password">
                            </div>

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


@endsection

@section('scripts')
<script>
    $('#adminLoginForm').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: "{{ route('admin.login.post') }}",
        method: "POST",
        data: $(this).serialize(),
        success: function(res){
            iziToast.success({
                title: 'Success',
                message: res.message,
                position: 'topRight'
            });
            setTimeout(() => {
                window.location.href = res.redirect;
            }, 1000);
        },
        error: function(xhr){
            let res = xhr.responseJSON;
            iziToast.error({
                title: 'Error',
                message: res.message,
                position: 'topRight'
            });
        }
    });
});
</script>
@endsection