<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\AccountType;
use App\Models\CryptoType;


class DepositController extends Controller
{
    public function create()
    {
        $accountTypes = AccountType::all();
        $cryptoTypes = CryptoType::all();

        return view('account.user.deposit', compact('accountTypes', 'cryptoTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|in:cheque,mobile,crypto',
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf',
            'amount' => 'required_if:method,mobile|nullable|numeric|min:1',
            'account_type_id' => 'required_if:method,mobile|nullable|exists:account_types,id',
            'crypto_type_id' => 'nullable|exists:crypto_types,id',
        ]);

        $deposit = new Deposit();
        $deposit->user_id = auth()->id();
        $deposit->method = $request->method;

        if ($request->method === 'mobile' || $request->method === 'crypto') {
            $deposit->amount = $request->amount;
            $deposit->account_type_id = $request->account_type_id;
            $deposit->crypto_type_id = $request->crypto_type_id;
        } else {
            // cheque - ensure these fields are null
            $deposit->amount = null;
            $deposit->account_type_id = null;
            $deposit->crypto_type_id = null;
        }

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('deposits', 'public');
            $deposit->proof_url = $path;
        }

        $deposit->save();

        return back()->with('success', 'Deposit submitted successfully!');
    }
}
