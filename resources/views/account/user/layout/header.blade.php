<!-- Top Nav -->
<div class="top-nav" id="topNav">
    <div class="d-flex align-items-center gap-3">
        <span class="menu-icon" id="menuIcon" title="Toggle menu">&#9776;</span>

        <!-- SEARCH: visible on md+ screens -->

    </div>

    <div class="d-flex align-items-center gap-2">

     

        <!-- NOTIFICATIONS -->
        <div class="dropdwn me-2">
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

                <!-- sample items -->
                 @forelse($recentActivities as $activity)
                <li>
                    <a class="dropdown-item d-flex align-items-start" href="/account/activities">
                        <div class="flex-shrink-0 me-2">
                            <i class="@if($activity->type == 'deposit' || $activity->type == 'self_credit') bi bi-arrow-down-circle text-success
                @elseif($activity->type == 'loan') bi bi-wallet2 text-primary
                @elseif($activity->type == 'profile') bi bi-person-circle text-secondary
                @elseif($activity->type == 'login') bi bi-box-arrow-in-right text-info
                @else bi bi-arrow-up-circle text-danger @endif
                fs-5 me-3 text-primary"></i></div>
                        <div class="flex-grow-1">
                            <div class="small text-muted">{{ $activity->type }}</div>
                            <div>{{ $activity->description }}</div>
                            <div class="small text-muted">2m ago</div>
                        </div>
                    </a>
                </li>
                @empty
                    <li class="list-group-item text-center text-muted">No recent activities</li>
                    @endforelse

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
            </ul>
        </div>

        <!-- Profile dropdown -->
        <div class="dropdwn">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/users/avatar-3.jpg') }}" class="mx-2 rounded-circle" width="40" height="40">
                <div class="d-none d-md-block">
                    <strong>{{Auth::user()->first_name}}</strong>
                    <div class="small text-muted">{{Auth::user()->email}}</div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/account/profile">Profile</a></li>
                
                <hr class="dropdown-divider">
                <li>
                </li>
                <li>
                    <form id="userLogoutForm" action="{{ route('logout') }}" method="POST" class="d-flex justify-content-center">
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