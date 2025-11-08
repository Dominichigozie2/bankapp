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


<body>

    {{-- Sidebar --}}
    @include('account.user.layout.sidebar')

    {{-- Header --}}
    @include('account.user.layout.header')

    <div class="main-content" id="mainContent">
        <div class="container-fluid">


            {{-- Main Content --}}
            @yield('content')


            @include('account.user.layout.footer')
        </div>

    </div>

    <!-- jQuery (required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/js/custom.js')}}"></script>

    <script src="{{asset("assets/libs/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <script src="{{asset("assets/libs/metismenujs/metismenujs.min.js")}}"></script>
    <script src="{{asset("assets/libs/simplebar/simplebar.min.js")}}"></script>
    <script src="{{asset("assets/libs/feather-icons/feather.min.js")}}"></script>

    <!-- apexcharts -->
    <script src="{{asset("assets/libs/apexcharts/apexcharts.min.js")}}"></script>

    <!-- Vector map-->
    <script src="{{asset("asses/libs/jsvectormap/js/jsvectormap.min.js")}}"></script>
    <script src="{{asset("assets/libs/jsvectormap/maps/world-merc.js")}}"></script>

    <!-- swiper js -->
    <script src="{{asset("assets/libs/swiper/swiper-bundle.min.js")}}"></script>

    <script src="{{asset("assets/js/pages/dashboard.init.js")}}"></script>

    <script>
        document.getElementById('closeSidebar').addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });



        const sidebar = document.getElementById('sidebar');
        const menuIcon = document.getElementById('menuIcon');
        const closeSidebar = document.getElementById('closeSidebar');
        const notifBadge = document.getElementById('notifBadge');
        const markAllReadBtn = document.getElementById('markAllReadBtn');

        menuIcon.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        closeSidebar.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                sidebar.classList.remove('active');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        // Search: submit handler (example)
        document.getElementById('adminSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const q = document.getElementById('adminSearchInput').value.trim();
            if (!q) return;
            // Replace this with actual search route or AJAX call
            alert('Search for: ' + q);
        });

        // Notifications: Mark all read (client-side example)
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Example behavior: hide badge and disable button.
            notifBadge.style.display = 'none';
            markAllReadBtn.textContent = 'All read';
            markAllReadBtn.disabled = true;

            // TODO: Call server endpoint to mark notifications read
            // fetch('/admin/notifications/mark-read', { method: 'POST', headers: {...}, body: ... })
        });

        // Close mobile sidebar when clicking outside (optional)
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !document.getElementById('topNav').contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>

    @yield('scripts')


</body>


</html>