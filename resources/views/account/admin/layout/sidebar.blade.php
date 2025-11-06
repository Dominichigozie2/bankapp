<div class="vertical-menu" style="transition: all 0.3s;" id="sidebar">
    <div data-simplebar class="h-100" style="display: flex; flex-direction: column;
    justify-content: space-between;">
        <div class="logo" style="width: 100px;padding: 1rem; gap: 1rem; align-items: center; display: flex;">
            <img src="/assets/images/logo-sm.svg" style="width: 70px;" alt="">
            <h4>Logo</h4>
        </div>
        <!--- Sidebar Menu -->
        <div id="sidebar-menu" class="mm-active" style="margin-top: 8rem;">
            <ul class="metismenu list-unstyled sidelist" id="side-menu">
                <li class="menu-title">Main</li>

                <li>
                    <a href="/admin/dashboard" class="waves-effect active waves-light">
                        <i class="bx bx-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="mb-2">
                    <a
                        class="text-white text-decoration-none d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#DepositSubmenu"
                        role="button"
                        aria-expanded="false"
                        aria-controls="DepositSubmenu">
                        <span><i class="bx bx-wallet"></i>
                            Deposit</span>
                        <i class="bx bx-chevron-down"></i>
                    </a>

                    <!-- Submenu -->
                    <ul class="collapse list-unstyled ps-4 mt-2" id="DepositSubmenu">
                        <li><a href="/admin/deposit">Deposit</a></li>
                        <li><a href="/admin/depositcodes">Deposit Settings</a></li>
                    </ul>
                </li>


                <li>
                    <a href="/admin/cards">
                        <i class="bx bx-card"></i>
                        <span>Card</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/user">
                        <i class="bx bx-user"></i>
                        <span>User</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/kyc">
                        <i class="bx bx-check"></i>
                        <span>KYC</span>
                    </a>
                </li>

                <li>
                    <a href="/admin/loans">
                        <i class="bx bx-arrow-to-bottom"></i>
                        <span>Loan</span>
                    </a>
                </li>

                <li class="mb-2">
                    <a
                        class="text-white text-decoration-none d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse"
                        href="#SettingsSubmenu"
                        role="button"
                        aria-expanded="false"
                        aria-controls="SettingsSubmenu">
                        <span><i class='bx bx-slider'></i>
                            Settings</span>
                        <i class="bx bx-chevron-down"></i>
                    </a>

                    <!-- Submenu -->
                    <ul class="collapse list-unstyled ps-4 mt-2" id="SettingsSubmenu">
                        <li><a href="/admin/codes">Generate Transfer Code</a></li>
                        <li><a href="/admin/transfer">Enable Transfers</a></li>
                    </ul>
                </li>


            </ul>

        </div>

        <ul class="logout" style="margin-top: 2rem;">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
            </li>
        </ul>
        <!-- Sidebar -->
    </div>
</div>