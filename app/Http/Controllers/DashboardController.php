<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAccount;
use App\Models\Deposit;
// use App\Models\Withdrawal;
use App\Models\Loan;
use App\Models\AccountType;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Fetch account type IDs
        $currentType = AccountType::where('name', 'current')->first();
        $savingsType = AccountType::where('name', 'savings')->first();

        // Get user accounts based on type IDs
        $currentAccount = $currentType
            ? UserAccount::where('user_id', $user->id)
                ->where('account_type_id', $currentType->id)
                ->first()
            : null;

        $savingsAccount = $savingsType
            ? UserAccount::where('user_id', $user->id)
                ->where('account_type_id', $savingsType->id)
                ->first()
            : null;

        $currentAccountNumber = $currentAccount ? $currentAccount->account_number : 'N/A';
        $savingsAccountNumber = $savingsAccount ? $savingsAccount->account_number : 'N/A';

        // Compute balances (adjust according to your logic)
        $currentBalance = $currentAccount ? $currentAccount->account_amount : 0;
        $savingsBalance = $savingsAccount ? $savingsAccount->account_amount : 0;

        // Fetch deposits and withdrawals
        $totalDeposits = Deposit::where('user_id', $user->id)->count();
        // $totalWithdrawals = Withdrawal::where('user_id', $user->id)->count();

        // Fetch loans if applicable
        $loanBalance = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('amount');

        // Recent deposits
        $recentDeposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get(['amount', 'created_at']);

        // Recent activities (if you create the activities table)
        $recentActivities = Activity::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get(['description', 'created_at']);

        // Return to dashboard view
        return view('account.user.index', compact(
            'currentBalance',
            'totalDeposits',
            // 'totalWithdrawals',
            'loanBalance',
            'savingsBalance',
            'currentAccountNumber',
            'savingsAccountNumber',
            'recentDeposits',
            'recentActivities'
        ));
    }
}
