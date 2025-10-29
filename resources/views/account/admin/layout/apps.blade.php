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
    <link rel="shortcut icon" href="{{asset("assets/images/favicon.ico")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/admin.bundle-9LCnK6yO.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/layout-Ce54t42U.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-1-X5ReqfrQ.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-2-Cb_CDfj7.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-3-cKscmnau.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-4-CDSU48Bo.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-5-B2nGwUY6.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-9-CesdsO47.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-7-ag73MNKM.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-8-C3KjVnOI.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/user-10-DKla81qp.js")}}" />
    <link rel="modulepreload" crossorigin href="assets/user-12-CSVOJxbn.js">
    <link rel="modulepreload" crossorigin href="assets/user-13-BCLG3jB2.js">
    <link rel="modulepreload" crossorigin href="assets/user-14-DT5Zm7w4.js">
    <link rel="modulepreload" crossorigin href="{{asset("assets/helper-8qJgsijo.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/main-1p2CIAJu.js")}}" />
    <link rel="modulepreload" crossorigin href="{{asset("assets/virtual-select-B8FSWoNa.js")}}">
    <link rel="stylesheet" crossorigin href="{{asset("assets/css/admin.css")}}" />
</head>

<body x-data="{ scrolled: false }" @scroll.window="scrolled = (window.scrollY > 0)">
    
     {{-- Include header and sidebar --}}
    @include('account.admin.layout.header')
    @include('account.admin.layout.sidebar')
    <!-- ✅ Page Content -->
        @yield('content')
    
    <script type="module" crossorigin src="assets/js/index.js"></script>
    <script src="{{asset("assets/libs/swiper/swiper-bundle.min.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/apps-event-overview.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/admin.bundle-9LCnK6yO.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/layout-Ce54t42U.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/main-1p2CIAJu.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/apps-school-teachers-list.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/apps-school-teachers-overview.js")}}"></script>
    <script src="{{asset("assets/libs/dayjs/dayjs.min.js")}}"></script>
    <script src="{{asset("assets/libs/dayjs/plugin/quarterOfYear.js")}}"></script>
    <script src="{{asset("assets/libs/swiper/swiper-bundle.min.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/apps-school-teachers-payroll.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/admin.bundle-9LCnK6yO.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/auth-signin-basic.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/pages-account-security.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/pages-account-security.js")}}"></script>
    <script type="module" crossorigin src="{{asset("assets/js/pages-account-statements.js")}}"></script>
</body>

</html>