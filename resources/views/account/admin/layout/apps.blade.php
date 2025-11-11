<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sales Dashboard | Vuesy - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- plugin css -->
    <link href="{{asset("assets/libs/jsvectormap/css/jsvectormap.min.css")}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.3.96/css/materialdesignicons.min.css">

    <!-- IZITOAST -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

    <!-- bootstrapicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.3.96/css/materialdesignicons.min.css">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- swiper css -->
    <link rel="stylesheet" href="{{asset("assets/libs/swiper/swiper-bundle.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/libs/swiper/swiper-bundle.min.css")}}">

    <!-- Bootstrap Css -->
    <link href="{{asset("assets/css/bootstrap.min.css")}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset("assets/css/icons.min.css")}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset("assets/css/app.min.css")}}" id="app-style" rel="stylesheet" type="text/css" />

    <link href="{{asset("assets/css/custom.css")}}" rel="stylesheet" type="text/css" />

    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">


</head>


<style>
    .body-content{
        margin-bottom: 5rem;
    }
</style>

<body>

    {{-- Sidebar --}}
    @include('account.admin.layout.sidebar')

    {{-- Header --}}
    @include('account.admin.layout.header')

    <div class="main-content" id="mainContent">
        <div class="container-fluid body-content">


            {{-- Main Content --}}
            @yield('content')


            @include('account.admin.layout.footer')
        </div>

    </div>

    <!-- jQuery (required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/js/custom.js')}}"></script>
    



    @yield('scripts')


</body>


</html>