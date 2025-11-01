<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function dashboard()
{
    // Demo data for the wallet
    $currentBalance = 1250.75;
    $totalDeposits = 15;
    $totalWithdrawals = 5;
    $loanBalance = 200.00;
    $savingsBalance = 500.00;
    $currentAccountNumber = '0123456789';
    $savingsAccountNumber = '9876543210';

    $recentDeposits = [
        ['amount' => 200, 'created_at' => '2025-10-29 14:20'],
        ['amount' => 150, 'created_at' => '2025-10-28 11:15'],
    ];

    $recentActivities = [
        ['description' => 'Bought airtime', 'created_at' => '2025-10-29 15:00'],
        ['description' => 'Received payment', 'created_at' => '2025-10-28 12:30'],
    ];

    return view('account.user.index', compact(
        'currentBalance',
        'totalDeposits',
        'totalWithdrawals',
        'loanBalance',
        'savingsBalance',
        'currentAccountNumber',
        'savingsAccountNumber',
        'recentDeposits',
        'recentActivities'
    ));
}

}
