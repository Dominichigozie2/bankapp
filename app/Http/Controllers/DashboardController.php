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

        // Fetch all user accounts with their account types
        $userAccounts = UserAccount::with('accountType')
            ->where('user_id', $user->id)
            ->get();

        // Prepare balances dynamically
        $balances = [];
        $accountNumbers = [];
        foreach ($userAccounts as $account) {
            $typeName = strtolower($account->accountType->name); // e.g., 'savings', 'current', 'loan'
            $balances[$typeName] = $account->account_amount;
            $accountNumbers[$typeName] = $account->account_number;
        }

        // Ensure keys exist even if the user doesn't have certain account types
        $balances['savings'] = $balances['savings'] ?? 0;
        $balances['current'] = $balances['current'] ?? 0;
        $balances['loan'] = $balances['loan'] ?? 0;

        $accountNumbers['savings'] = $accountNumbers['savings'] ?? 'N/A';
        $accountNumbers['current'] = $accountNumbers['current'] ?? 'N/A';
        $accountNumbers['loan'] = $accountNumbers['loan'] ?? 'N/A';

        // Total deposits count
        $totalDeposits = Deposit::where('user_id', $user->id)->count();

        // Loan balance sum
        $loanBalance = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('amount');

        // Recent deposits
        $recentDeposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get(['amount', 'created_at']);

        // Recent activities
        $recentActivities = Activity::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get(['description', 'created_at']);

        return view('account.user.index', compact(
            'user',
            'balances',
            'accountNumbers',
            'recentDeposits',
            'recentActivities',
            'loanBalance',
            'totalDeposits',
            'userAccounts'
        ));
    }
}
