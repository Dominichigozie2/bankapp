<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\AccountType;
use App\Models\AdminSetting;
use App\Models\UserCode;
use Illuminate\Support\Facades\Auth;
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

  public function verifyMultipleCodes(Request $request)
    {
        $settings = AdminSetting::first();
        $user = Auth::user();

        // validation: allow missing codes if not enabled but still validate lengths if present
        $rules = [];
        if ($settings->cot_enabled) $rules['cot_code'] = 'required|string|max:255';
        if ($settings->tax_enabled) $rules['tax_code'] = 'required|string|max:255';
        if ($settings->imf_enabled) $rules['imf_code'] = 'required|string|max:255';

        $validated = $request->validate($rules);

        // normalize incoming codes
        $inCot = $request->input('cot_code') ? strtoupper(trim($request->input('cot_code'))) : null;
        $inTax = $request->input('tax_code') ? strtoupper(trim($request->input('tax_code'))) : null;
        $inImf = $request->input('imf_code') ? strtoupper(trim($request->input('imf_code'))) : null;

        $errors = [];

        // fetch user codes (if any)
        $userCodes = UserCode::where('user_id', $user->id)->first();

        // helper to check one code: returns true if match
        $check = function($in, $global, $userSpecific) {
            if (is_null($in)) return false;
            $in = strtoupper(trim($in));
            if ($global && strtoupper(trim($global)) === $in) return true;
            if ($userSpecific && strtoupper(trim($userSpecific)) === $in) return true;
            return false;
        };

        if ($settings->cot_enabled) {
            $global = $settings->global_cot_code;
            $userSpecific = $userCodes->cot_code ?? null;
            if (! $check($inCot, $global, $userSpecific)) {
                $errors['cot_code'] = ['Invalid COT code'];
            }
        }

        if ($settings->tax_enabled) {
            $global = $settings->global_tax_code;
            $userSpecific = $userCodes->tax_code ?? null;
            if (! $check($inTax, $global, $userSpecific)) {
                $errors['tax_code'] = ['Invalid TAX code'];
            }
        }

        if ($settings->imf_enabled) {
            $global = $settings->global_imf_code;
            $userSpecific = $userCodes->imf_code ?? null;
            if (! $check($inImf, $global, $userSpecific)) {
                $errors['imf_code'] = ['Invalid IMF code'];
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'One or more codes are invalid.',
                'errors' => $errors
            ], 422);
        }

        return response()->json(['message' => 'All required codes verified.']);
    }

}


