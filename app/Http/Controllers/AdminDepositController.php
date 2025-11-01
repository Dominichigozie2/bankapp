<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;

class AdminDepositController extends Controller
{
    /**
     * Display all deposits for admin
     */
    public function index()
    {
        $deposits = Deposit::with(['user', 'accountType', 'cryptoType'])
            ->latest()
            ->paginate(10);

        return view('account.admin.deposit', compact('deposits'));
    }

    /**
     * Approve a deposit
     */
    public function approve($id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->status = 'approved';
        $deposit->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Deposit approved successfully!'
        ]);
    }

    /**
     * Reject a deposit
     */
    public function reject($id)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->status = 'rejected';
        $deposit->save();

        return response()->json([
            'status' => 'error',
            'message' => 'Deposit rejected.'
        ]);
    }
}
