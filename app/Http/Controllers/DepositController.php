<?php

namespace App\Http\Controllers;
use App\Mail\DepositNotification; // make sure this is imported
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\UserAccount;
use App\Models\AdminSetting;
use App\Models\CryptoType;
use App\Models\Setting;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DepositController extends Controller
{
    public function create()
    {
        $cryptoTypes = CryptoType::all();
        $user = auth()->user();
        $activeAccount = $user->activeAccount ? $user->activeAccount->accountType : null;
        $userAccounts = $user->accounts()->with('accountType')->get();

        return view('account.user.deposit', compact('userAccounts', 'cryptoTypes', 'activeAccount'));
    }

public function store(Request $request)
{
    $request->validate([
        'method' => 'required|in:cheque,mobile,crypto',
        'proof' => 'required|file|mimes:jpg,jpeg,png,pdf',
        'amount' => 'required_if:method,mobile|nullable|numeric|min:1',
        'user_account_id' => 'required_if:method,mobile|nullable|exists:user_accounts,id',
        'crypto_type_id' => 'nullable|exists:crypto_types,id',
    ]);

    $user = auth()->user();

    $deposit = new Deposit();
    $deposit->user_id = $user->id;
    $deposit->user_account_id = $request->user_account_id ?? null;
    $deposit->method = $request->method;
    $deposit->amount = $request->amount ?? null;
    $deposit->crypto_type_id = $request->crypto_type_id ?? null;

    if ($request->hasFile('proof')) {
        $path = $request->file('proof')->store('deposits', 'public');
        $deposit->proof_url = $path;
    }

    // Set status based on method
    if ($request->method === 'cheque') {
        $deposit->status = 'pending';
        $deposit->amount = null; // no amount credited yet
    } elseif ($request->method === 'mobile') {
        $deposit->status = 'approved';
    } else {
        $deposit->status = 'approved'; // default for crypto or other future methods
    }

    $deposit->save();

    // Credit the user's account if it's not a cheque
    if ($deposit->user_account_id && $deposit->amount && $request->method !== 'cheque') {
        $account = $user->accounts()->where('id', $deposit->user_account_id)->first();
        if ($account) {
            $account->account_amount += $deposit->amount;
            $account->save();
        }
    }

    // Log activity
    Activity::create([
        'user_id' => $user->id,
        'description' => "Submitted {$request->method} deposit of " . ($deposit->amount ?? 'N/A') . " (ID: {$deposit->id})",
        'type' => 'deposit',
    ]);

    // Send DepositSubmitted email to admin after submission
    $siteEmail = AdminSetting::first()?->site_email;
    if ($siteEmail) {
        Mail::to($siteEmail)->send(new \App\Mail\DepositSubmitted($deposit, $user));
    }

    return back()->with('success', 'Deposit submitted successfully!');
}



    public function codeRequired()
    {
        $val = Setting::get('deposit_code_required', '0');
        return response()->json(['required' => $val == '1']);
    }

    public function verifyCode(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        $normalize = fn($v) => $v ? strtoupper(trim($v)) : null;
        $errors = [];
        $valid = true;

        if ($settings->cot_enabled && !is_null($request->cot_code) && $normalize($request->cot_code) !== $normalize($settings->global_cot_code)) {
            $errors['cot'] = 'Invalid COT code';
            $valid = false;
        }
        if ($settings->tax_enabled && !is_null($request->tax_code) && $normalize($request->tax_code) !== $normalize($settings->global_tax_code)) {
            $errors['tax'] = 'Invalid TAX code';
            $valid = false;
        }
        if ($settings->imf_enabled && !is_null($request->imf_code) && $normalize($request->imf_code) !== $normalize($settings->global_imf_code)) {
            $errors['imf'] = 'Invalid IMF code';
            $valid = false;
        }

        if (!$valid) {
            return response()->json([
                'success' => false,
                'message' => 'One or more codes are incorrect',
                'errors' => $errors
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Code verified successfully.'
        ]);
    }

    public function verifySingleCode(Request $request)
    {
        $request->validate([
            'code_type' => 'required|in:cot,tax,imf',
            'code' => 'required|string'
        ]);

        $settings = AdminSetting::first();
        $type = $request->input('code_type');
        $code = strtoupper(trim($request->input('code')));
        $normalize = fn($v) => $v ? strtoupper(trim($v)) : null;

        $fail = fn($msg) => response()->json(['success' => false, 'message' => $msg], 422);

        if ($type === 'cot') {
            if (!$settings->cot_enabled) return $fail('COT not required');
            if ($normalize($code) !== $normalize($settings->global_cot_code)) return $fail('Invalid COT code');
        } elseif ($type === 'tax') {
            if (!$settings->tax_enabled) return $fail('TAX not required');
            if ($normalize($code) !== $normalize($settings->global_tax_code)) return $fail('Invalid TAX code');
        } elseif ($type === 'imf') {
            if (!$settings->imf_enabled) return $fail('IMF not required');
            if ($normalize($code) !== $normalize($settings->global_imf_code)) return $fail('Invalid IMF code');
        }

        return response()->json(['success' => true, 'message' => strtoupper($type) . ' code verified']);
    }


    public function emailPreview(Request $request)
    {
        $request->validate([
            'method' => 'required|in:cheque,mobile,crypto',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'amount' => 'nullable|numeric|min:0',
            'user_account_id' => 'nullable|exists:user_accounts,id',
            'crypto_type_id' => 'nullable|exists:crypto_types,id',
        ]);

        $user = auth()->user();

        $siteEmail = AdminSetting::first()?->site_email; // fetch from table
        if ($siteEmail) {
            // Send the existing deposit_notification mailable
            Mail::to($siteEmail)->send(new DepositNotification($user, $request));
        }

        return response()->json(['success' => true, 'message' => 'Admin notified successfully.']);
    }
}
