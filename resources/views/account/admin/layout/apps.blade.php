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

    <!-- IZITOAST -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

    <!-- bootstrapicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <!-- swiper css -->
    <link rel="stylesheet" href="{{asset("assets/libs/swiper/swiper-bundle.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/libs/swiper/swiper-bundle.min.css")}}">

    <!-- Bootstrap Css -->
    <link href="{{asset("assets/css/bootstrap.min.css")}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset("assets/css/icons.min.css")}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset("assets/css/app.min.css")}}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

</head>

<style>
    #page-topbar {
        z-index: 100;
        color: #fff !important;
        position: sticky;
        top: 0;
        background-color: #371950ff;
    }

    .vertical-menu {
        position: stickyt;
        top: 0;
        flex-direction: column;
        display: flex;
        justify-content: center;
        flex-shrink: 0;
        z-index: 10;
    }

    .main-content {
        padding: 20px;
        background: #f8f9fc;
    }

    .sidelist {
        display: flex;
        flex-direction: column;
        gap: 1rem;

        li {
            width: 100%;

            a {
                display: flex;
                gap: 1rem;
                align-items: center;
                font-size: 12px;
                color: #707070ff !important;

                span {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                &:hover {
                    color: #9222e7ff !important;
                }
            }

            & .active {
                color: #9222e7ff !important;
            }
        }
    }

    .logout {
        li {
            width: 100%;
            list-style: none;

            a {
                display: flex;
                gap: 1rem;
                align-items: center;
                font-size: 14px;
                color: #707070ff !important;

                span {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                &:hover {
                    color: #9222e7ff !important;
                }
            }

            & .active {
                color: #9222e7ff !important;
            }
        }
    }
</style>

<body data-layout="vertical">
    <div id="layout-wrapper">
        {{-- Header --}}
        <div id="layout-wrapper">

            {{-- Header --}}
            @include('account.admin.layout.header')

            <div class="d-flex">
                {{-- Sidebar --}}
                @include('account.admin.layout.sidebar')

                {{-- Main Content --}}
                <div class="main-content flex-grow-1">
                    @yield('content')
                    @include('account.admin.layout.footer')
                </div>
            </div>

        </div>

    </div>
    <!-- jQuery (required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <!-- JAVASCRIPT -->
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

    <!-- <script src="{{asset("assets/js/app.js")}}"></script> -->
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const body = document.querySelector('body'); // No need for id

            sidebar.classList.toggle('collapsed');

            if (sidebar.classList.contains('collapsed')) {
                sidebar.style.marginLeft = "-250px";
                mainContent.style.marginLeft = "0";
                body.setAttribute('data-layout', 'horizontal');

            } else {
                sidebar.style.marginLeft = "0";
                mainContent.style.marginLeft = "250px";
                body.setAttribute('data-layout', 'horizontal');
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.pathname;
            const menuLinks = document.querySelectorAll("#side-menu li a");

            menuLinks.forEach(link => {
                const linkPath = link.getAttribute("href");

                // If link matches current page URL
                if (linkPath === currentPath) {
                    link.classList.add("active");

                    // If link is inside a submenu, expand its parent
                    const submenu = link.closest(".collapse");
                    if (submenu) {
                        submenu.classList.add("show"); // open submenu
                        const parentToggle = document.querySelector(
                            `[href="#${submenu.id}"]`
                        );
                        if (parentToggle) parentToggle.classList.add("active");
                    }
                } else {
                    link.classList.remove("active");
                }
            });
        });
    </script>

    @yield('scripts')
</body>



</html>