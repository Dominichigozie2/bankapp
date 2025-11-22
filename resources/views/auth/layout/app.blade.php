<!DOCTYPE html>
<html :lang="$data.currentLang" x-data="layoutSettings()" x-init="initialize()" class="scroll-smooth group"
    data-layout="modern" data-content-width="default" data-mode="light" data-sidebar="large" data-sidebar-colors="light"
    data-nav-type="pattern" dir="ltr" data-colors="violet">

<head>
    <meta charset="utf-8" />
    <title>
        School | Domiex - Premium Versatile Admin & Dashboard UI Kit Template
    </title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
    <meta
        content="Domiex is a Premium Versatile Admin & Dashboard UI Kit Template that supports 21 frameworks including HTML, React JS, React TS, Angular 18, Laravel 11, ASP.Net Core 8, MVC 5, Blazor, Node JS, Django, Flask, PHP, CakePHP, Symfony, CodeIgniter, Ajax & Yii and more. Perfect for developers and businesses."
        name="description" />
    <meta content="SRBThemes" name="author" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="" />
    <meta property="og:title" content="Domiex - Premium Versatile Admin & Dashboard UI Kit Template" />
    <meta property="og:description"
        content="Versatile and responsive admin templates supporting 21 frameworks. Includes features like charts, RTL, LTR, dark light modes, and more." />
    <meta property="twitter:url" content="" />
    <meta property="twitter:title" content="Domiex - Premium Versatile Admin & Dashboard UI Kit Template" />
    <meta property="twitter:description"
        content="Explore Domiex, an admin & dashboard template offering support for 21 frameworks. Perfect for building professional, scalable web apps." />
    <meta name="keywords"
        content="admin dashboard template, admin template, TailwindCSS dashboard, react admin, angular admin, laravel admin, 21 frameworks support, responsive dashboard, web application template, dark mode, RTL support, Vue, MVC, Blazor, PHP, Node.js, Django, Flask, Symfony, CodeIgniter" />
<link rel="modulepreload" crossorigin href="assets/admin.bundle-9LCnK6yO.js">
    <link rel="modulepreload" crossorigin href="{{asset("asset/layout-Ce54t42U.js")}}">
    <link rel="modulepreload" crossorigin href="{{asset("asset/main-1p2CIAJu.js")}}">
    <link rel="stylesheet" crossorigin href="{{asset("asset/css/admin.css")}}">
    <!-- iziToast CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">

</head>

<body x-data="{ scrolled: false }" @scroll.window="scrolled = (window.scrollY > 0)">

    <!-- ✅ Page Content -->
        @yield('content')

    <!-- jQuery (required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- iziToast JS -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>

    <script type="module" crossorigin src="{{asset("asset/js/auth-signin-basic.js")}}"></script>
    <script type="module" crossorigin src="{{asset("asset/admin.bundle-9LCnK6yO.js")}}"></script>
    <script type="module" crossorigin src="{{asset("asset/layout-Ce54t42U.js")}}"></script>
    <script type="module" crossorigin src="{{asset("asset/main-1p2CIAJu.js")}}"></script>
    <script>
// Automatically start loading on any button with .btn-submit
$(document).on('click', '.btn-submit', function(e) {
    let button = $(this);

    // Prevent multiple clicks
    if (button.prop("disabled")) return;

    startLoading(button);
});

// Start loading state
function startLoading(button) {
    button.prop("disabled", true);
    button.find(".btn-text").addClass("d-none");
    button.find(".spinner-border").removeClass("d-none");
}

// Stop loading state (call after AJAX)
function stopLoading(button) {
    button.prop("disabled", false);
    button.find(".spinner-border").addClass("d-none");
    button.find(".btn-text").removeClass("d-none");
}
</script>

    @yield('scripts')
</body>

</html>
