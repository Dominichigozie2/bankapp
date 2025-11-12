@extends('account.user.layout.app')
@section('content')

<!-- Welcome Modal -->
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="welcomeModalLabel">Welcome!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Hello, <strong>{{ Auth::user()->name }}</strong>! Welcome back to your dashboard.</p>
                <p>Have a productive day!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Start</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Profile & Balances Card -->
    <div class="col-lg-6 col-md-6">
        <div class="card border-0 shadow-lg rounded-50 position-relative">
            <a href="/account/deposit" class="btn btn-primary position-absolute top-0 end-0 m-3 rounded-circle" title="Add Deposit">
                <i class="bi bi-plus-lg"></i>
            </a>

            <div class="card-body text-center">
                <div class="row d-flex align-items-center gap-3 mb-3">
                    <div class="col-1">
                        <img width="40" height="40" class="rounded-circle mb-3"
                            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/users/avatar-3.jpg') }}"
                            alt="Profile">
                    </div>
                    <h5 class="mb-1 col-5 fw-bold text-start">{{ $user->first_name }}</h5>
                </div>

                <div class="row bg-primary text-white rounded-3 p-3 mx-1 my-3">
                    <p class="text-white small mb-3">Account Balance</p>
                    @php
                    $totalBalance = ($balances['current'] ?? 0) + ($balances['savings'] ?? 0) - ($balances['loan'] ?? 0);
                    @endphp
                    <h3 class="fw-semibold text-white mb-4">₦{{ number_format($totalBalance, 2) }}</h3>
                </div>

                <div class="row text-center mb-3">
                    <div class="col-4">
                        <small class="text-muted">Loan</small>
                        <div class="fw-bold text-danger">₦{{ number_format($balances['loan'], 2) }}</div>
                    </div>
                    <div class="col-4 border-start border-end">
                        <small class="text-muted">Savings</small>
                        <div class="fw-bold text-success">₦{{ number_format($balances['savings'], 2) }}</div>
                    </div>
                    <div class="col-4">
                        <small class="text-muted">Current</small>
                        <div class="fw-bold text-primary">₦{{ number_format($balances['current'], 2) }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-around mt-3">
                    <a href="/account/deposit" class="btn btn-outline-primary btn-sm d-flex align-items-center">
                        <i class="bi bi-wallet2 me-1"></i> Deposit
                    </a>
                    <a href="/account/transfer" class="btn btn-outline-success btn-sm d-flex align-items-center">
                        <i class="bi bi-arrow-left-right me-1"></i> Transfer
                    </a>
                    <a href="/account/deposit" class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                        <i class="bi bi-bank me-1"></i> To Bank
                    </a>
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
                @foreach($userAccounts as $account)
                <div class="mb-3">
                    <small class="text-muted d-block">{{ $account->accountType->name }} Account</small>
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ $account->account_number }}</strong>
                        <button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="{{ $account->account_number }}">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Recent Transactions and Activities -->
<div class="row g-4" style="margin-bottom: 2rem;">
    <!-- Recent Transactions -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Recent Transactions</h6>
                <a href="#" class="text-primary small">View All</a>
            </div>
            <div class="card-body">


                <ul class="list-group list-group-flush">
                    @forelse($recentTransactions as $deposit)
                    @php
                    $method = $deposit['method'] ?? 'deposit';

                    // Determine if this is a "debit" (negative) transaction
                    if(in_array($method, ['transfer_local', 'transfer_international'])) {
                    $iconClass = 'bi bi-arrow-up-circle text-danger';
                    $bgClass = 'bg-danger bg-opacity-10 text-danger';
                    $sign = '-';
                    $label = 'Transfer';
                    } else {
                    // All other transactions are credits
                    $iconClass = 'bi bi-arrow-down-circle text-success';
                    $bgClass = 'bg-success bg-opacity-10 text-success';
                    $sign = '+';

                    // Determine label
                    if($method == 'deposit') $label = 'Deposit';
                    elseif($method == 'self_credit') $label = 'Self Credit';
                    elseif($method == 'loan') $label = 'Loan Payment';
                    else $label = ucfirst($method); // fallback label
                    }
                    @endphp

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-start">
                            <div class="icon-box {{ $bgClass }} rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                <i class="{{ $iconClass }} fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $label }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($deposit['created_at'])->format('M d, Y') }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold {{ $sign == '+' ? 'text-success' : 'text-danger' }}">
                                {{ $sign }} ₦{{ number_format($deposit['amount'], 2) }}
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">No recent transactions</li>
                    @endforelse
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
                    @forelse($recentActivities as $activity)
                    <li class="list-group-item d-flex align-items-start">
                        <i class="
                @if($activity->type == 'deposit' || $activity->type == 'self_credit') bi bi-arrow-down-circle text-success
                @elseif($activity->type == 'loan') bi bi-wallet2 text-primary
                @elseif($activity->type == 'profile') bi bi-person-circle text-secondary
                @elseif($activity->type == 'login') bi bi-box-arrow-in-right text-info
                @else bi bi-arrow-up-circle text-danger @endif
                fs-5 me-3"></i>
                        <div>
                            <div class="fw-semibold">{{ $activity->description }}</div>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">No recent activities</li>
                    @endforelse
                </ul>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@if($showWelcome)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
        welcomeModal.show();
    });
</script>
@endif

<script>
    // Copy account number
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