@extends('account.user.layout.app')
@section('content')

<div class="row g-4">
    <!-- Profile & Balances Card -->
    <div class="col-lg-6 col-md-6">
        <div class="card border-0 shadow-lg rounded-50 position-relative">
            <!-- Deposit icon -->
            <button class="btn btn-primary position-absolute top-0 end-0 m-3 rounded-circle" title="Add Deposit">
                <i class="bi bi-plus-lg"></i>
            </button>

            <div class="card-body text-center">
                <!-- Profile -->
                <div class="row">
                    <div class="col-1">
                        <img width="20" height="20" class="rounded-circle" src="{{ asset("assets/images/users/avatar-1.jpg") }}" class="rounded-circle mb-3" alt="Profile">
                    </div>
                    <h5 class="mb-1 col-5 fw-bold text-start">John Doe</h5>
                </div>
                <div class="row bg-primary text-white rounded-3 p-3 mx-1 my-3">

                    <p class="text-white small mb-3">Account Balance</p>

                    <!-- Main Balance -->
                    <h3 class="fw-semibold text-white mb-4">₦250,000.00</h3>
                </div>

                <!-- Mini Balances -->
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <small class="text-muted">Loan</small>
                        <div class="fw-bold text-danger">₦40,000</div>
                    </div>
                    <div class="col-4 border-start border-end">
                        <small class="text-muted">Savings</small>
                        <div class="fw-bold text-success">₦150,000</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Current</small>
                        <div class="fw-bold text-primary">₦60,000</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-around mt-3">
                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center">
                        <i class="bi bi-wallet2 me-1"></i> Deposit
                    </button>
                    <button class="btn btn-outline-success btn-sm d-flex align-items-center">
                        <i class="bi bi-arrow-left-right me-1"></i> Transfer
                    </button>
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="bi bi-bank me-1"></i> To Bank
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Numbers Card -->
    <div class="col-lg-6 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold">
                Account Numbers
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Savings Account</small>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>0123456789</strong>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="0123456789">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <small class="text-muted d-block">Current Account</small>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>9876543210</strong>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="9876543210">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
                <hr>
                <div>
                    <small class="text-muted d-block">Loan Account</small>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>1100220033</strong>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="1100220033">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Recent Transactions and Activities -->
<div class="row g-4" style="margin-bottom: 2rem;">

    <!-- Recent Transactions -->
    <!-- Recent Transactions -->
<div class="col-lg-8">
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
      <h6 class="fw-bold mb-0">Recent Transactions</h6>
      <a href="#" class="text-primary small">View All</a>
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        
        <!-- Transaction Item -->
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-start">
            <div class="icon-box bg-primary bg-opacity-10 text-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
              <i class="bi bi-arrow-down-circle text-primary fs-5"></i>
            </div>
            <div>
              <div class="fw-semibold">Self Credit</div>
              <small class="text-muted">Nov 7, 2025</small>
            </div>
          </div>
          <div class="text-end">
            <div class="fw-semibold text-success">+ ₦50,000.00</div>
          </div>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-start">
            <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
              <i class="bi bi-arrow-up-circle fs-5"></i>
            </div>
            <div>
              <div class="fw-semibold">Transfer to John</div>
              <small class="text-muted">Nov 5, 2025</small>
            </div>
          </div>
          <div class="text-end">
            <div class="fw-semibold text-danger">- ₦15,000.00</div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>


    <!-- Recent Activities -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Recent Activities</h6>
                <a href="#" class="text-primary small">View All</a>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-start">
                        <i class="bi bi-arrow-down-circle text-success fs-5 me-3"></i>
                        <div>
                            <div class="fw-semibold">Deposit Successful</div>
                            <small class="text-muted">₦50,000 added to savings</small><br>
                            <small class="text-muted">2 hrs ago</small>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-start">
                        <i class="bi bi-arrow-up-circle text-danger fs-5 me-3"></i>
                        <div>
                            <div class="fw-semibold">Transfer Initiated</div>
                            <small class="text-muted">₦15,000 to John Doe</small><br>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-start">
                        <i class="bi bi-wallet2 text-primary fs-5 me-3"></i>
                        <div>
                            <div class="fw-semibold">Loan Payment</div>
                            <small class="text-muted">₦25,000 credited</small><br>
                            <small class="text-muted">4 days ago</small>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-start">
                        <i class="bi bi-exclamation-circle text-warning fs-5 me-3"></i>
                        <div>
                            <div class="fw-semibold">Bill Payment Failed</div>
                            <small class="text-muted">₦5,000 not deducted</small><br>
                            <small class="text-muted">1 week ago</small>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script>
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const value = btn.dataset.copy;
            navigator.clipboard.writeText(value);
            btn.innerHTML = '<i class="bi bi-check text-success"></i>';
            setTimeout(() => (btn.innerHTML = '<i class="bi bi-clipboard"></i>'), 1500);
        });
    });
</script>

@endsection