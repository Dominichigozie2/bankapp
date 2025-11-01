<style>
    /* Gradient Card Styling */
    .credit-card {
        width: 100%;
        max-width: 400px;
        height: 18rem;
        border-radius: 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }

    .credit-card .chip {
        width: 50px;
        height: 35px;
        background: linear-gradient(180deg, #e0e0e0, #bdbdbd);
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .credit-card .card-number {
        font-size: 1.1rem;
        letter-spacing: 3px;
        margin-bottom: 15px;
    }

    .credit-card .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .credit-card .card-name {
        font-weight: 600;
        font-size: 1rem;
    }

    .credit-card .amount {
        font-size: 1rem;
        font-weight: bold;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 10px;
        border-radius: 10px;
    }
</style>
@extends('account.user.layout.app')
@section('content')
<div class="page-content">

    <div class="page-content">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center"> <!-- Credit Card Preview -->
                    <div class="credit-card mx-auto mb-4">
                        <div class="chip"></div>
                        <div class="card-number">**** **** **** 1234</div>
                        <div class="card-footer">
                            <div class="card-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                            <div class="amount">${{ number_format(Auth::user()->balance ?? 0, 2) }}</div>
                        </div>
                    </div>
                    <!-- Request Card Form -->
                    <div class="card shadow-sm  border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Request New Card</h5>
                            <form id="requestCardForm">
                                @csrf
                                <div class="mb-3 text-start">
                                    <label for="accountType" class="form-label">Select Account Type</label>
                                    <select class="form-select" id="accountType" name="account_type" required>
                                        <option value="">Choose...</option>
                                        <option value="savings">Savings Account</option>
                                        <option value="current">Current Account</option>
                                        <option value="business">Business Account</option>
                                    </select>
                                </div>
                                <div class="mb-3 text-start">
                                    <label for="pinCode" class="form-label">Account PIN Code</label>
                                    <input type="password" id="pinCode" name="pin" class="form-control" placeholder="Enter 4-digit PIN" maxlength="4" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Request Card</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    @endsection
    @section('scripts')
    @endsection