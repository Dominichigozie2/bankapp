<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAccount;
use App\Models\Deposit;
use App\Models\Transfer;
use App\Models\Loan;
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

        // Active loan balance
        $loanBalance = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->sum('amount');

        /**
         * 🟢 Recent Transactions (Deposits + Transfers + Loans)
         * Keep as Eloquent collections until merging
         */
        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $transfers = Transfer::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $loans = Loan::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Merge all transactions and sort by date descending
        $recentTransactions = $deposits->merge($transfers)->merge($loans)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        // Convert to arrays for the view
        $recentTransactions = $recentTransactions->map(fn($item) => [
            'method' => $item->method ?? ($item->type ?? 'unknown'),
            'amount' => $item->amount,
            'created_at' => $item->created_at,
            'type' => $item instanceof Deposit ? 'deposit' : ($item instanceof Transfer ? 'transfer' : 'loan'),
        ]);

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

        // Return the view with all data
        return view('account.user.index', compact(
            'user',
            'balances',
            'accountNumbers',
            'recentTransactions',
            'recentActivities',
            'loanBalance',
            'totalDeposits',
            'userAccounts',
            'showWelcome'
        ));
    }
}
