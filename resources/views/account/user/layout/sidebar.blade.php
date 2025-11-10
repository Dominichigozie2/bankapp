<div class="sidebar d-flex flex-column" id="sidebar">
  <div class="logo d-flex justify-content-between align-items-center px-3 py-2">
    <img src="/assets/images/logo-sm.svg" width="30px" alt="">
    <h4 class="m-0"><span>AdminPanel</span></h4>
    <button class="btn-close btn-close-white" id="closeSidebar"></button>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="/account/dashboard" class="nav-link active text-white"><i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span></a>
    </li>

    <li class="nav-item">
      <a href="/account/deposit" class="nav-link text-white"><i class="bi bi-wallet2 me-2"></i> <span>Deposits</span></a>
    </li>

    <li class="nav-item">
      <a href="/account/transfer" class="nav-link text-white"><i class="bi bi-arrow-left-right me-2"></i> <span>Transfers</span></a>
    </li>

    <li class="nav-item">
      <a href="/account/cards" class="nav-link text-white"><i class="bi bi-credit-card-2-back me-2"></i> <span>Card</span></a>
    </li>
    <li class="nav-item dropbtn">
      <a class="nav-link text-white">
        <i class="bi bi-bank me-2"></i>
        <span>loans <i class="bi bi-caret-down-fill"></i></span>
      </a>
      <ul class="dropdown">
        <li><a href="/account/loans"><span>loans</span></a></li>
        <li><a href="/account/loanhistory"><span>Loan history</span></a></li>
      </ul>
    </li>

    <li class="nav-item dropbtn">
      <a class="nav-link text-white">
        <i class="bi bi-person me-2"></i>
        <span>My Account <i class="bi bi-caret-down-fill"></i></span>
      </a>
      <ul class="dropdown">
        <li><a href="/account/profile"><span>Profile</span></a></li>
        <li><a href="/account/kyc"><span>KYC Verification</span></a></li>

        <li class="subdropbtn">
          <a class="text-white">
            <span>Securty <i class="bi bi-caret-down-fill"></i></span>
          </a>
          <ul class="subdropdown">
            <li><a href="/account/activity"><span>Activities</span></a></li>
            <li><a href="/account/report"><span>Report</span></a></li>
          </ul>
        </li>
      </ul>
    </li>
    <li class="nav-item dropbtn">
      <a class="nav-link text-white">
        <i class="bi bi-patch-question me-2"></i>
        <span>Need Help? <i class="bi bi-caret-down-fill "></i></span>
      </a>
      <ul class="dropdown">
        <li><a href="/account/report"><span>New ticket</span></a></li>
        <li><a href="/account/tickethistory"><span>View Tickets</span></a></li>
        <li><a href="/account/help"><span>Helpdesk</span></a></li>
      </ul>
    </li>


  </ul>

  <div class="logout px-3 py-2 mt-auto border-top border-secondary">
    <a href="#" class="nav-link text-white">
      <i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span>
    </a>
  </div>
</div>