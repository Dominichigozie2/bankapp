        @extends('account.user.layout.app')
        @section('content')
        <div class="page-content">
                <div class="container-fluid py-4">

                    {{-- Wallet Top Card --}}
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-primary text-white shadow-sm text-center p-4 rounded">
                                <h5 class="fw-light text-white">Current Balance</h5>
                                <h2 class="fw-bold text-white">${{ number_format($currentBalance, 2) }}</h2>
                                <div class="mt-3">
                                    <button class="btn btn-light btn-sm me-2">Deposit</button>
                                    <button class="btn btn-light btn-sm">Withdraw</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Cards --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm p-3 rounded">
                                <h6>Total Deposits</h6>
                                <h4>{{ $totalDeposits }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm p-3 rounded">
                                <h6>Total Withdrawals</h6>
                                <h4>{{ $totalWithdrawals }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm p-3 rounded">
                                <h6>Loan Balance</h6>
                                <h4>${{ number_format($loanBalance, 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm p-3 rounded">
                                <h6>Savings Balance</h6>
                                <h4>${{ number_format($savingsBalance, 2) }}</h4>
                            </div>
                        </div>
                    </div>

                    {{-- Accounts --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm p-3 rounded">
                                <h6>Current Account</h6>
                                <p class="mb-0">{{ $currentAccountNumber }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm p-3 rounded">
                                <h6>Savings Account</h6>
                                <p class="mb-0">{{ $savingsAccountNumber }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Deposits --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm p-3 rounded">
                                <h6>Recent Deposits</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($recentDeposits as $deposit)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        ${{ $deposit['amount'] }}
                                        <span class="text-muted small">{{ $deposit['created_at'] }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Recent Activities --}}
                        <div class="col-md-6">
                            <div class="card shadow-sm p-3 rounded">
                                <h6>Recent Activities</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($recentActivities as $activity)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $activity['description'] }}
                                        <span class="text-muted small">{{ $activity['created_at'] }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        <!-- End Page-content -->

        @endsection
        @section('scripts')
        @endsection