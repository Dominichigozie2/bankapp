<div class="vertical-menu" style="transition: all 0.3s;" id="sidebar">
    <div data-simplebar class="h-100">

        <!--- Sidebar Menu -->
        <div id="sidebar-menu" class="mm-active" style="margin-top: 8rem;">
            <ul class="metismenu list-unstyled sidelist" id="side-menu">
                <li class="menu-title">Main</li>

                <li>
                    <a href="/account/dashboard" class="waves-effect active waves-light">
                        <i class="bx bx-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="/account/transfer">
                        <i class="bx bx-send"></i>
                        <span>Transfers</span>
                    </a>
                </li>

                <li>
                    <a href="/account/deposit">
                        <i class="bx bx-wallet"></i>
                        <span>Deposits</span>
                    </a>
                </li>

                <li class="mb-2">
                    <a
                        class="text-white text-decoration-none d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#transferSubmenu"
                        role="button"
                        aria-expanded="false"
                        aria-controls="transferSubmenu">
                        <span>

                            <i class="bx bx-arrow-to-bottom"></i>
                            Loan</span>
                        <i class="bx bx-chevron-down"></i>
                    </a>

                    <!-- Submenu -->
                    <ul class="collapse list-unstyled ps-4 mt-2" id="transferSubmenu">
                        <li><a href="/account/loans" class="text-white-50 text-decoration-none d-block py-2">Loan</a></li>
                        <li><a href="/account/loanhistory" class="text-white-50 text-decoration-none d-block py-2">Loan History</a></li>
                    </ul>
                </li>

                <li>
                    <a href="/account/cards">
                        <i class="bx bx-card"></i>
                        <span>Card</span>
                    </a>
                </li>

                <li class="mb-2">
                    <a
                        class="text-white text-decoration-none d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#profileSubmenu"
                        role="button"
                        aria-expanded="false"
                        aria-controls="profileSubmenu">
                        <span><i class="bx bx-user"></i>
                            My Account</span>
                        <i class="bx bx-chevron-down"></i>
                    </a>

                    <!-- Submenu -->
                    <ul class="collapse list-unstyled ps-4 mt-2" id="profileSubmenu">
                        <li><a href="/account/profile" class="text-white-50 text-decoration-none d-block py-2">Profile</a></li>
                        <li><a href="/account/kyc" class="text-white-50 text-decoration-none d-block py-2">KYC</a></li>
                    </ul>
                </li>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>