<div class="sidebar d-flex flex-column" id="sidebar">
  <div class="logo d-flex justify-content-between align-items-center px-3 py-2">
    <img src="/assets/images/logo-sm.svg" width="30px" alt="">
    <h4 class="m-0"><span>AdminPanel</span></h4>
    <button class="btn-close btn-close-white" id="closeSidebar"></button>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="/admin/dashboard" class="nav-link active text-white"><i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span></a>
    </li>

    <li style="background: #220f32ff;"> Funding</li>
    <li class="nav-item">
      <a href="/admin/deposit" class="nav-link text-white"><i class="bi bi-arrow-down-circle me-2"></i> <span>Deposit</span></a>
    </li>
    <li class="nav-item">
      <a href="/admin/transfers" class="nav-link text-white"><i class="bi bi-arrow-left-right me-2"></i> <span>Transfers</span></a>
    </li>

    <li class="nav-item">
      <a href="/admin/cards" class="nav-link text-white"><i class="bi bi-credit-card-2-back me-2"></i> <span>Card</span></a>
    </li>

    <li class="nav-item">
      <a href="/admin/loans" class="nav-link text-white"><i class="bi bi-bank me-2"></i> <span>loans</span></a>
    </li>

    <li class="nav-item">
      <a href="/admin/credit-debit" class="nav-link text-white"><i class="bi bi-arrows-fullscreen me-2"></i> <span>Credit/Debit user</span></a>
    </li>

    <li style="background: #220f32ff;"> User Management</li>
    <li class="nav-item">
      <a href="/admin/user" class="nav-link text-white"><i class="bi bi-person me-2"></i> <span>User</span></a>
    </li>
    <li class="nav-item">
      <a href="/admin/kyc" class="nav-link text-white"><i class="bi bi-person-check me-2"></i> <span>kyc Verification</span></a>
    </li>


    <li style="background: #220f32ff;"> Settings & Profile</li>

    <li class="nav-item">
      <a href="/admin/profile" class="nav-link text-white"><i class="bi bi-person-gear me-2"></i> <span>Edit Profile</span></a>
    </li>

    <li class="nav-item dropbtn">
      <a class="nav-link text-white">
        <i class="bi bi-patch-question me-2"></i>
        <span>SyStem Settings <i class="bi bi-caret-down-fill "></i></span>
      </a>
      <ul class="dropdown">
        <li><a href="/admin/transfer_settings"><span>Settings</span></a></li>
        <li><a href="/admin/tickets"><span>Tickets</span></a></li>
        <li><a href="/admin/crypto_types"><span>Crypto</span></a></li>
        <li><a href="/admin/accounttypes"><span>Add Account types</span></a></li>
      </ul>
    </li>



    <div class="logout px-3 py-2 mt-auto border-top border-secondary text-white">
      <form id="adminLogoutForm" action="{{ route('admin.logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn">
          
          <i class="bi bi-box-arrow-right me-2 text-white"></i> <span class="text-white">Logout</span>
          
        </button>
      </form>

    </div>
</div>