<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Transfer;
use App\Models\AdminSetting;
use App\Models\UserCode;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{

  public function index()
    {
        $user = Auth::user();

    $userAccounts = UserAccount::where('user_id', $user->id)->get();
    $settings = AdminSetting::first();

    return view('account.user.transfer', compact('user', 'userAccounts', 'settings'));
    }

    // POST endpoint to start a local transfer (passcode required)
    public function storeLocal(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        // check global transfers enabled
        if (!$settings->transfers_enabled) {
            return response()->json(['success' => false, 'message' => 'Transfers are temporarily disabled. Contact support.'], 403);
        }

        // validate input
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'details' => 'nullable|string',
            'passcode' => 'required|string'
        ]);

        // max transfer check
        if ($validated['amount'] > $settings->max_transfer_amount) {
            return response()->json(['success' => false, 'message' => 'Amount exceeds maximum transfer limit.'], 422);
        }

        // check user not restricted
        if ($user->transfer_restricted) {
            return response()->json(['success' => false, 'message' => 'Your transfer access is restricted. Contact support.'], 403);
        }

        // check passcode
        if ($user->passcode !== $validated['passcode']) {
            return response()->json(['success' => false, 'message' => 'Incorrect account code.'], 401);
        }

        // create transfer
        $transfer = Transfer::create([
            'user_id' => $user->id,
            'type' => 'local',
            'amount' => $validated['amount'],
            'bank_name' => $validated['bank_name'],
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'details' => $validated['details'] ?? null,
            'reference' => strtoupper(Str::random(10)),
            'status' => 'pending'
        ]);

        // optionally apply service charge or store it in meta
        $transfer->meta = ['service_charge' => $settings->service_charge];
        $transfer->save();

        return response()->json([
            'success' => true,
            'message' => $settings->transfer_success_message,
            'redirect' => route('user.transfers.history')
        ]);
    }

    // POST for international transfer (handles COT/TAX/IMF checks)
    public function storeInternational(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        // validate core fields (codes are validated below)
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'bank_country' => 'required|string',
            'routine_number' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'details' => 'nullable|string',
            // passcode and codes can be included in the same request
            'passcode' => 'required|string',
            'cot_code' => 'nullable|string',
            'tax_code' => 'nullable|string',
            'imf_code' => 'nullable|string',
        ]);

        // global checks
        if (!$settings->transfers_enabled) {
            return response()->json(['success' => false, 'message' => 'Transfers are temporarily disabled.'], 403);
        }
        if ($user->transfer_restricted) {
            return response()->json(['success' => false, 'message' => 'Your transfer access is restricted. Contact support.'], 403);
        }
        if ($validated['amount'] > $settings->max_transfer_amount) {
            return response()->json(['success' => false, 'message' => 'Amount exceeds maximum transfer limit.'], 422);
        }

        // verify codes according to admin settings
        $userCodes = UserCode::firstOrNew(['user_id' => $user->id]);

        if ($settings->cot_enabled && ($validated['cot_code'] !== $userCodes->cot_code)) {
            return response()->json(['success' => false, 'message' => 'Invalid COT code.'], 401);
        }
        if ($settings->tax_enabled && ($validated['tax_code'] !== $userCodes->tax_code)) {
            return response()->json(['success' => false, 'message' => 'Invalid TAX code.'], 401);
        }
        if ($settings->imf_enabled && ($validated['imf_code'] !== $userCodes->imf_code)) {
            return response()->json(['success' => false, 'message' => 'Invalid IMF code.'], 401);
        }

        // passcode check
        if ($user->passcode !== $validated['passcode']) {
            return response()->json(['success' => false, 'message' => 'Incorrect account code.'], 401);
        }

        // create transfer
        $transfer = Transfer::create([
            'user_id' => $user->id,
            'type' => 'international',
            'amount' => $validated['amount'],
            'bank_name' => $validated['bank_name'],
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'bank_country' => $validated['bank_country'],
            'routine_number' => $validated['routine_number'] ?? null,
            'bank_code' => $validated['bank_code'] ?? null,
            'details' => $validated['details'] ?? null,
            'reference' => strtoupper(Str::random(10)),
            'status' => 'pending',
            'meta' => [
                'cot_code' => $validated['cot_code'] ?? null,
                'tax_code' => $validated['tax_code'] ?? null,
                'imf_code' => $validated['imf_code'] ?? null,
                'service_charge' => $settings->service_charge,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => $settings->transfer_success_message,
            'redirect' => route('user.transfers.history')
        ]);
    }

    // history view
    public function history()
    {
        $user = Auth::user();
        $transfers = Transfer::where('user_id', $user->id)->latest()->paginate(10);
        return view('account.user.transfer_history', compact('transfers'));
    }

    // invoice view
    public function invoice($id)
    {
        $user = Auth::user();
        $transfer = Transfer::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        return view('account.user.transfer_invoice', compact('transfer'));
    }

 public function selfTransfer(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'amount' => 'required|numeric|min:1',
        'from_account' => 'required|different:to_account',
        'to_account' => 'required',
        'passcode' => 'required|min:6|max:6',
    ]);

    // ✅ Verify passcode
    if ($request->passcode !== $user->passcode) {
        return response()->json(['success' => false, 'message' => 'Incorrect account passcode']);
    }

    // ✅ Optional: check if balance is enough (if your accounts have balances)
    $fromAccount = UserAccount::where('id', $request->from_account)
        ->where('user_id', $user->id)
        ->first();

    if (!$fromAccount) {
        return response()->json(['success' => false, 'message' => 'Invalid source account']);
    }

    if (isset($fromAccount->balance) && $fromAccount->balance < $request->amount) {
        return response()->json(['success' => false, 'message' => 'Insufficient funds in source account']);
    }

    // ✅ Insert into transfers table
    $transfer = Transfer::create([
        'user_id' => $user->id,
        'type' => 'self',
        'amount' => $request->amount,
        'bank_name' => 'Internal Transfer',
        'account_name' => $user->name,
        'account_number' => $fromAccount->account_number ?? 'SELF',
        'details' => "Transfer from Account #{$request->from_account} to Account #{$request->to_account}",
        'reference' => strtoupper(Str::random(10)),
        'status' => 'pending',
        'meta' => [
            'from_account' => $request->from_account,
            'to_account' => $request->to_account,
        ],
    ]);

    // ✅ Optional: deduct balance immediately
    // $fromAccount->decrement('balance', $request->amount);
    // $toAccount->increment('balance', $request->amount);

    return response()->json([
        'success' => true,
        'message' => 'Self transfer recorded successfully! Pending admin confirmation.',
        'transfer_id' => $transfer->id,
    ]);
}
protected $casts = [
    'meta' => 'array',
    'amount' => 'decimal:2',
];

}
