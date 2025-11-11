<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use App\Models\Transfer;
use App\Models\Card;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminQuickEmail;
use App\Models\AccountType;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all(); // fetch all users

        // 1️⃣ Total Balance: sum of all active accounts
        $totalBalance = UserAccount::where('is_active', true)->sum('account_amount');

        // 2️⃣ Total Wire Transfers (international)
        $totalWire = Transfer::where('type', 'international')
            ->where('status', 'success')
            ->sum('amount');

        // 3️⃣ Total Domestic Transfers (local)
        $totalDomestic = Transfer::where('type', 'local')
            ->where('status', 'success')
            ->sum('amount');

        // 4️⃣ Current & Savings balances
        $currentBalance = UserAccount::whereHas('accountType', function ($q) {
            $q->where('name', 'Current');
        })->where('is_active', true)->sum('account_amount');

        $savingsBalance = UserAccount::whereHas('accountType', function ($q) {
            $q->where('name', 'Savings');
        })->where('is_active', true)->sum('account_amount');

        // 5️⃣ Total active cards
        $totalCards = Card::where('card_status', 1)->count();

        // 6️⃣ Total open tickets
        $totalTickets = Ticket::where('status', 'open')->count();

        // Total Users
        $totalUsers = User::count();

        // Latest 5 users
        $recentUsers = User::latest()->take(5)->get();

        // Pass all data to the view
        return view('account.admin.index', compact(
            'totalBalance',
            'totalWire',
            'totalDomestic',
            'currentBalance',
            'savingsBalance',
            'totalCards',
            'totalTickets',
            'totalUsers',
            'recentUsers',
            'users'
        ));
    }

    public function sendEmail(Request $request)
    {
        // Validate inputs
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Optional: check if user exists
        $user = User::where('email', $request->to)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }

        // Send email
        Mail::to($request->to)->send(new AdminQuickEmail($request->subject, $request->body));

        return response()->json(['success' => true, 'message' => 'Email sent successfully!']);
    }



     // Show form
    public function creditDebitForm()
    {
        $users = User::all(); // all users
        $accounts = AccountType::all(); // all account types
        return view('account.admin.creditdebit', compact('users', 'accounts'));
    }

    // Handle form submission
    public function creditDebitProcess(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'account_type_id' => 'required|exists:account_types,id',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
            'action' => 'required|in:create,debit',
        ]);

        // get user's active account for the chosen type
        $userAccount = UserAccount::where('user_id', $request->user_id)
            ->where('account_type_id', $request->account_type_id)
            ->first();

        if(!$userAccount){
            return back()->withErrors(['user_id' => 'User does not have this account type']);
        }

        if($request->action === 'create'){
            $userAccount->account_amount += $request->amount;
        } else { // debit
            if($userAccount->account_amount < $request->amount){
                return back()->withErrors(['amount' => 'Insufficient balance']);
            }
            $userAccount->account_amount -= $request->amount;
        }

        $userAccount->save();

        return back()->with('success', 'Transaction completed successfully');
    }


}
