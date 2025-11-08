<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
            background: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #111827;
            color: #fff;
            transition: all .28s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar .logo h4 {
            margin: 0;
            font-size: 1.05rem;
            transition: opacity .2s;
        }

        .sidebar.collapsed .logo h4 {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar .close-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.25rem;
            cursor: pointer;
            display: none;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 14px;
            flex-grow: 1;
        }

        .sidebar ul li {
            padding: 12px 18px;
            cursor: pointer;
            transition: background .15s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar ul li:hover,
        .sidebar ul li.active {
            background: #1f2937;
        }

        .sidebar ul li i {
            font-size: 1.05rem;
        }

        .sidebar.collapsed ul li span {
            display: none;
        }

        .sidebar .logout {
            padding: 14px 18px;
            background: #dc3545;
            text-align: center;
            cursor: pointer;
        }

        /* Top Nav */
        .top-nav {
            height: 64px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.06);
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            transition: left .28s, width .28s;
            z-index: 900;
        }

        .sidebar.collapsed~.top-nav {
            left: 70px;
            width: calc(100% - 70px);
        }

        .menu-icon {
            cursor: pointer;
            font-size: 1.45rem;
        }

        .main-content {
            margin-top: 64px;
            margin-left: 250px;
            padding: 20px;
            transition: margin-left .28s;
        }

        .sidebar.collapsed~.main-content {
            margin-left: 70px;
        }

        @media (max-width:992px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar .close-btn {
                display: inline;
            }

            .top-nav {
                left: 0;
                width: 100%;
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* search input smaller on mobile */
        .top-nav .search-wrapper {
            max-width: 520px;
            width: 100%;
        }

        @media (max-width:576px) {
            .top-nav .search-wrapper {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <h4>AdminPanel</h4>
            <button class="close-btn" id="closeSidebar" title="Collapse sidebar">&times;</button>
        </div>

        <ul>
            <li class="active"><i class="bi bi-speedometer2"></i><span>Dashboard</span></li>
            <li><i class="bi bi-wallet2"></i><span>Deposits</span></li>
            <li><i class="bi bi-arrow-left-right"></i><span>Transfers</span></li>
            <li><i class="bi bi-people"></i><span>Users</span></li>
            <li><i class="bi bi-gear"></i><span>Settings</span></li>
        </ul>

        <div class="logout">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </div>
    </div>

    <!-- Top Nav -->
    <div class="top-nav" id="topNav">
        <div class="d-flex align-items-center gap-3">
            <span class="menu-icon" id="menuIcon" title="Toggle menu">&#9776;</span>

            <!-- SEARCH: visible on md+ screens -->
            <div class="search-wrapper d-none d-md-block">
                <form id="adminSearchForm" class="d-flex">
                    <input id="adminSearchInput" class="form-control form-control-sm me-2" type="search" placeholder="Search users, transfers, deposits..." aria-label="Search">
                    <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- NOTIFICATIONS -->
            <div class="dropdown me-2">
                <button class="btn btn-light position-relative" id="notifDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width:320px;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                        <span>Notifications</span>
                        <button class="btn btn-link btn-sm" id="markAllReadBtn">Mark all read</button>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <!-- sample items — replace with server-fed items -->
                    <li>
                        <a class="dropdown-item d-flex align-items-start" href="#">
                            <div class="flex-shrink-0 me-2"><i class="bi bi-wallet2 fs-4 text-primary"></i></div>
                            <div class="flex-grow-1">
                                <div class="small text-muted">Deposits</div>
                                <div>New deposit awaiting approval — #DEP-1023</div>
                                <div class="small text-muted">2m ago</div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-start" href="#">
                            <div class="flex-shrink-0 me-2"><i class="bi bi-person-check fs-4 text-success"></i></div>
                            <div class="flex-grow-1">
                                <div class="small text-muted">KYC</div>
                                <div>User John Doe submitted KYC</div>
                                <div class="small text-muted">1h ago</div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-start" href="#">
                            <div class="flex-shrink-0 me-2"><i class="bi bi-exclamation-triangle fs-4 text-warning"></i></div>
                            <div class="flex-grow-1">
                                <div class="small text-muted">System</div>
                                <div>Scheduled maintenance at 02:00 UTC</div>
                                <div class="small text-muted">6h ago</div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                </ul>
            </div>

            <!-- Profile dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://via.placeholder.com/40" alt="Admin" class="rounded-circle me-2" width="40" height="40">
                    <div class="d-none d-md-block">
                        <strong>Admin</strong>
                        <div class="small text-muted">admin@example.com</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="container-fluid">
            <h2 class="mb-4">Welcome, Admin 👋</h2>
            <div class="card p-5 text-center">
                <p>This is your main admin page body. Add widgets, charts, or tables here.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
</body>

</html>