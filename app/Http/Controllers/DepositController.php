<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\AccountType;
use App\Models\CryptoType;
use App\Models\DepositCode;
use App\Models\Setting;

class DepositController extends Controller
{
    
    public function create()
{
    $cryptoTypes = CryptoType::all();
    // load the logged in user's active account(s)
    $user = auth()->user();
    $activeAccount = $user->activeAccount ? $user->activeAccount->accountType : null;
    // if you want to let user choose among their accounts, pass user->accounts()
    $userAccounts = $user->accounts()->with('accountType')->get();

    return view('account.user.deposit', compact('userAccounts','cryptoTypes','activeAccount'));
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


public function codeRequired()
{
    $val = Setting::get('deposit_code_required', '0');
    return response()->json(['required' => $val == '1']);
}

public function verifyCode(Request $request)
{
    $request->validate([
        'code' => 'required|string',
    ]);

    $code = strtoupper($request->code);

    $depositCode = \App\Models\DepositCode::where('code', $code)
        ->where('status', 'active')
        ->first();

    if (!$depositCode) {
        return response()->json(['status' => 'error', 'message' => 'Invalid or already used deposit code.'], 404);
    }

    // mark as used immediately or reserve it for the user
    $depositCode->update([
        'status' => 'used',
        'user_id' => auth()->id(),
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Deposit code verified successfully!',
    ]);
}


}


