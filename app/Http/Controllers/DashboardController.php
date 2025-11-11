<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAccount;
use App\Models\Deposit;
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
            $typeName = strtolower($account->accountType->name);
            $balances[$typeName] = $account->account_amount;
            $accountNumbers[$typeName] = $account->account_number;
        }

        // Ensure keys exist even if missing
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
            ->get(['amount', 'created_at', 'method']);


        // Recent activities
        $recentActivities = Activity::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Show welcome modal only once per session
        $showWelcome = false;
        if (!session()->has('welcome_shown')) {
            session(['welcome_shown' => true]);
            $showWelcome = true;
        }

        return view('account.user.index', compact(
            'user',
            'balances',
            'accountNumbers',
            'recentDeposits',
            'recentActivities',
            'loanBalance',
            'totalDeposits',
            'userAccounts',
            'showWelcome'
        ));
    }
}
