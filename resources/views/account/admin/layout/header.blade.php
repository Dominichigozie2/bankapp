<!-- Top Nav -->
<div class="top-nav" id="topNav">
    <div class="d-flex align-items-center gap-3">
        <span class="menu-icon" id="menuIcon" title="Toggle menu">&#9776;</span>

        <!-- SEARCH: visible on md+ screens -->

    </div>

    <div class="d-flex align-items-center gap-2">

        
        <!-- Profile dropdown -->
        <div class="dropdwn">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/users/avatar-3.jpg') }}" class="mx-2" width="40" height="40">
                <div class="d-none d-md-block">
                    <strong>{{Auth::user()->first_name}}</strong>
                    <div class="small text-muted">{{Auth::user()->email}}</div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/admin/profile">Profile</a></li>
                <li><a class="dropdown-item" href="/admin/transfer_settings">Settings</a></li>
                <hr class="dropdown-divider">
                <li>
                </li>
                <li>
                    <form id="adminLogoutForm" action="{{ route('admin.logout') }}" method="POST" class="d-flex justify-content-center">
                        @csrf
                        <button type="submit" class="btn text-danger">
                            Logout
                        </button>
                    </form>

                </li>
            </ul>
        </div>

    </div>
</div>