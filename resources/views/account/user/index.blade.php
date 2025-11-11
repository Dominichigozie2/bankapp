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

                    <!-- Main Balance -->
                    @php
                    $totalBalance = ($balances['current'] ?? 0) + ($balances['savings'] ?? 0) - ($balances['loan'] ?? 0);
                    @endphp
                    <h3 class="fw-semibold text-white mb-4">₦{{ number_format($totalBalance, 2) }}</h3>
                </div>

                <!-- Mini Balances -->
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
                    @forelse($recentDeposits as $deposit)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-start">
                            <div class="icon-box 
                                    @if($deposit->type == 'deposit' || $deposit->type == 'self_credit') bg-primary bg-opacity-10 text-success 
                                    @else bg-danger bg-opacity-10 text-danger @endif
                                    rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                <i class="
                                        @if($deposit->type == 'deposit' || $deposit->type == 'self_credit') bi bi-arrow-down-circle text-primary
                                        @else bi bi-arrow-up-circle text-danger @endif
                                        fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">
                                    @if($deposit->type == 'deposit') Deposit
                                    @elseif($deposit->type == 'self_credit') Self Credit
                                    @elseif($deposit->type == 'loan') Loan Payment
                                    @else Transfer @endif
                                </div>
                                <small class="text-muted">{{ $deposit->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold 
                                    @if($deposit->type == 'deposit' || $deposit->type == 'self_credit' || $deposit->type == 'loan') text-success 
                                    @else text-danger @endif">
                                @if($deposit->type == 'deposit' || $deposit->type == 'self_credit' || $deposit->type == 'loan') + @else - @endif ₦{{ number_format($deposit->amount, 2) }}
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
                                @else bi bi-arrow-up-circle text-danger @endif
                                fs-5 me-3"></i>
                        <div>
                            <div class="fw-semibold">{{ $activity->description }}</div>
                            <small class="text-muted">{{ $activity->amount ? '₦'.number_format($activity->amount,2) : '' }} {{ $activity->note ?? '' }}</small><br>
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