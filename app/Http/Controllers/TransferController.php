<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\AdminSetting;
use App\Models\UserAccount;
use App\Models\Activity;
use App\Mail\TransferNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /** Show transfer page */
    public function index()
    {
        $user = Auth::user();
        $userAccounts = UserAccount::where('user_id', $user->id)->get();
        $settings = AdminSetting::first();

        return view('account.user.transfer', compact('user', 'userAccounts', 'settings'));
    }

    /** Local Transfer */
    public function storeLocal(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        if (!$settings->transfers_enabled) {
            return response()->json(['success' => false, 'message' => 'Transfers are temporarily disabled.'], 403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'account' => 'required|exists:user_accounts,id',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'details' => 'nullable|string',
            'passcode' => 'required|string',
        ]);

        if ($user->passcode !== $validated['passcode']) {
            return response()->json(['success' => false, 'message' => 'Incorrect account passcode.'], 401);
        }

        if ($user->transfer_restricted) {
            return response()->json(['success' => false, 'message' => 'Your transfer access is restricted.'], 403);
        }

        $serviceCharge = $settings->service_charge ?? 0;
        $totalRequired = $validated['amount'] + $serviceCharge;

        $fromAccount = UserAccount::where('id', $validated['account'])
            ->where('user_id', $user->id)
            ->first();

        if (!$fromAccount || $fromAccount->account_amount < $totalRequired) {
            return response()->json(['success' => false, 'message' => 'Insufficient account balance.'], 422);
        }

        // Deduct from account
        $fromAccount->account_amount -= $totalRequired;
        $fromAccount->save();

        $transfer = Transfer::create([
            'user_id' => $user->id,
            'type' => 'local',
            'amount' => $validated['amount'],
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'details' => $validated['details'] ?? null,
            'reference' => strtoupper(Str::random(10)),
            'status' => 'pending',
            'meta' => [
                'service_charge' => $serviceCharge,
                'from_account' => $validated['account'],
            ],
        ]);

        Activity::create([
            'user_id' => $user->id,
            'description' => "Submitted local transfer of {$validated['amount']} (ref: {$transfer->reference})",
            'type' => 'transfer',
        ]);

        $details = [
            'subject' => 'Local Transfer Submitted',
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'message' => "Your local transfer request has been submitted and is pending approval.",
            'amount' => $validated['amount'],
            'type' => 'local',
        ];
        Mail::to($user->email)->send(new TransferNotificationMail($details));

        return response()->json([
            'success' => true,
            'message' => $settings->transfer_success_message ?? 'Local transfer submitted successfully!',
            'redirect' => route('user.transfers.history')
        ]);
    }

    /** International Transfer */
    public function storeInternational(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'account' => 'required|exists:user_accounts,id',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'bank_country' => 'required|string',
            'routine_number' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'details' => 'nullable|string',
            'cot_code' => 'nullable|string',
            'tax_code' => 'nullable|string',
            'imf_code' => 'nullable|string',
            'codes_verified' => 'nullable|boolean',
        ]);

        if (!$settings->transfers_enabled || $user->transfer_restricted) {
            return response()->json(['success' => false, 'message' => 'Transfers are disabled or restricted.'], 403);
        }

        if ($validated['amount'] > $settings->max_transfer_amount) {
            return response()->json(['success' => false, 'message' => 'Amount exceeds maximum transfer limit.'], 422);
        }

        $serviceCharge = $settings->service_charge ?? 0;
        $totalRequired = $validated['amount'] + $serviceCharge;

        $fromAccount = UserAccount::where('id', $validated['account'])
            ->where('user_id', $user->id)
            ->first();

        if (!$fromAccount || $fromAccount->account_amount < $totalRequired) {
            return response()->json(['success' => false, 'message' => 'Insufficient account balance.'], 422);
        }

        // Validate codes if required
        if (empty($validated['codes_verified'])) {
            $normalize = fn($v) => $v ? strtoupper(trim($v)) : null;
            $errors = [];
            $valid = true;

            if ($settings->cot_enabled && $normalize($validated['cot_code']) !== $normalize($settings->global_cot_code)) {
                $errors['cot'] = 'Invalid COT code';
                $valid = false;
            }
            if ($settings->tax_enabled && $normalize($validated['tax_code']) !== $normalize($settings->global_tax_code)) {
                $errors['tax'] = 'Invalid TAX code';
                $valid = false;
            }
            if ($settings->imf_enabled && $normalize($validated['imf_code']) !== $normalize($settings->global_imf_code)) {
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
        }

        // Deduct from account
        $fromAccount->account_amount -= $totalRequired;
        $fromAccount->save();

        $transfer = Transfer::create([
            'user_id' => $user->id,
            'type' => 'international',
            'amount' => $validated['amount'],
            'bank_name' => $validated['bank_name'],
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
            'bank_country' => $validated['bank_country'],
            'routine_number' => $validated['routine_number'] ?? null,
            'bank_code' => $validated['bank_code'] ?? null,
            'details' => $validated['details'] ?? null,
            'reference' => strtoupper(Str::random(10)),
            'status' => 'pending',
            'meta' => [
                'service_charge' => $serviceCharge,
                'from_account' => $validated['account'],
                'cot_code' => $validated['cot_code'] ?? null,
                'tax_code' => $validated['tax_code'] ?? null,
                'imf_code' => $validated['imf_code'] ?? null,
            ],
        ]);

        Activity::create([
            'user_id' => $user->id,
            'description' => "Submitted international transfer of {$validated['amount']} (ref: {$transfer->reference})",
            'type' => 'transfer',
        ]);

        $details = [
            'subject' => 'International Transfer Submitted',
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'message' => "Your international transfer request has been submitted and is pending approval.",
            'amount' => $validated['amount'],
            'type' => 'international',
        ];
        Mail::to($user->email)->send(new TransferNotificationMail($details));

        return response()->json([
            'success' => true,
            'message' => $settings->transfer_success_message,
            'redirect' => route('user.transfers.history')
        ]);
    }

    /** Self/Internal Transfer */
    public function selfTransfer(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'from_account' => 'required|different:to_account',
            'to_account' => 'required',
            'passcode' => 'required|min:6|max:6',
        ]);

        if ($request->passcode !== $user->passcode) {
            return response()->json(['success' => false, 'message' => 'Incorrect account passcode']);
        }

        $fromAccount = UserAccount::where('id', $request->from_account)->where('user_id', $user->id)->first();
        $toAccount = UserAccount::where('id', $request->to_account)->where('user_id', $user->id)->first();

        if (!$fromAccount || !$toAccount) {
            return response()->json(['success' => false, 'message' => 'Invalid source or destination account']);
        }

        if ($fromAccount->account_amount < $request->amount) {
            return response()->json(['success' => false, 'message' => 'Insufficient account balance'], 422);
        }

        // Perform debit and credit in transaction
        DB::transaction(function () use ($fromAccount, $toAccount, $request, $user) {
            $fromAccount->account_amount -= $request->amount;
            $fromAccount->save();

            $toAccount->account_amount += $request->amount;
            $toAccount->save();

            $transfer = Transfer::create([
                'user_id' => $user->id,
                'type' => 'self',
                'amount' => $request->amount,
                'bank_name' => 'Internal Transfer',
                'account_name' => $toAccount->account_name ?? $user->first_name . ' ' . $user->last_name ?? $user->email,
                'account_number' => $fromAccount->account_number,
                'details' => "Transfer from Account #{$fromAccount->id} to Account #{$toAccount->id}",
                'reference' => strtoupper(Str::random(10)),
                'status' => 'pending',
                'meta' => [
                    'from_account' => $fromAccount->id,
                    'from_account_name' => $fromAccount->account_name,
                    'to_account' => $toAccount->id,
                    'to_account_name' => $toAccount->account_name,
                ],
            ]);

            Activity::create([
                'user_id' => $user->id,
                'description' => "Submitted internal transfer of {$request->amount} from account #{$fromAccount->id} to #{$toAccount->id} (ref: {$transfer->reference})",
                'type' => 'transfer',
            ]);

            // Send email notification
            $details = [
                'subject' => 'Self Transfer Submitted',
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'message' => "Your internal transfer request has been submitted and is pending admin confirmation.",
                'amount' => $request->amount,
                'type' => 'self',
            ];
            Mail::to($user->email)->send(new TransferNotificationMail($details));
        });

        return response()->json([
            'success' => true,
            'message' => 'Self transfer recorded successfully! Pending admin confirmation.',
        ]);
    }

    /** Transfer history */
    public function history()
    {
        $user = Auth::user();
        $transfers = Transfer::where('user_id', $user->id)->latest()->paginate(10);
        return view('account.user.transfer_history', compact('transfers'));
    }

    /** Verify codes for transfers */
    public function verifyCodes(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        $normalize = fn($v) => $v ? strtoupper(trim($v)) : null;
        $errors = [];
        $valid = true;

        if ($settings->cot_enabled && empty($request->cot_code)) {
            $errors['cot'] = 'COT code is required';
            $valid = false;
        } elseif ($settings->cot_enabled && $normalize($request->cot_code) !== $normalize($settings->global_cot_code)) {
            $errors['cot'] = 'Invalid COT code';
            $valid = false;
        }

        if ($settings->tax_enabled && empty($request->tax_code)) {
            $errors['tax'] = 'TAX code is required';
            $valid = false;
        } elseif ($settings->tax_enabled && $normalize($request->tax_code) !== $normalize($settings->global_tax_code)) {
            $errors['tax'] = 'Invalid TAX code';
            $valid = false;
        }

        if ($settings->imf_enabled && empty($request->imf_code)) {
            $errors['imf'] = 'IMF code is required';
            $valid = false;
        } elseif ($settings->imf_enabled && $normalize($request->imf_code) !== $normalize($settings->global_imf_code)) {
            $errors['imf'] = 'Invalid IMF code';
            $valid = false;
        }

        if (!$valid) {
            return response()->json(['success' => false, 'message' => 'One or more codes are missing or incorrect.', 'errors' => $errors], 422);
        }

        return response()->json(['success' => true, 'message' => 'Code verified successfully.']);
    }

    /** Verify single code */
    public function verifySingleCode(Request $request)
    {
        $request->validate([
            'code_type' => 'required|in:cot,tax,imf',
            'code' => 'required|string'
        ]);

        $type = $request->input('code_type');
        $code = strtoupper(trim($request->input('code')));
        $settings = AdminSetting::first();
        $normalize = fn($v) => $v ? strtoupper(trim($v)) : null;

        $fail = fn($msg = 'Invalid code') => response()->json(['success' => false, 'message' => $msg], 422);

        if ($type === 'cot') {
            if (!$settings->cot_enabled) return $fail('COT not required');
            if ($normalize($code) !== $normalize($settings->global_cot_code)) return $fail('Invalid COT code');
        }
        if ($type === 'tax') {
            if (!$settings->tax_enabled) return $fail('TAX not required');
            if ($normalize($code) !== $normalize($settings->global_tax_code)) return $fail('Invalid TAX code');
        }
        if ($type === 'imf') {
            if (!$settings->imf_enabled) return $fail('IMF not required');
            if ($normalize($code) !== $normalize($settings->global_imf_code)) return $fail('Invalid IMF code');
        }

        return response()->json(['success' => true, 'message' => strtoupper($type) . ' verified']);
    }

    /** Transfer Invoice */
    public function invoice($id)
    {
        $transfer = Transfer::findOrFail($id);

        if ($transfer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this transfer.');
        }

        return view('account.user.transfer_invoice', compact('transfer'));
    }


    /**
     * Preview transfer email to admin before actual submission
     */
    public function emailPreview(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:local,international',
            'amount' => 'required|numeric|min:0',
            'account_name' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $user = Auth::user();
        $siteEmail = AdminSetting::first()?->site_email;

        if ($siteEmail) {
            $details = [
                'subject' => 'Transfer Submitted',
                'user_name' => $user->first_name . ' ' . $user->last_name,
                'recipient_name' => $validated['account_name'], // for local/internal transfers
                'amount' => $validated['amount'],
                'type' => $validated['type'], // 'local' or 'international'
                'message' => $validated['message'] ?? 'Your transfer request has been submitted.', // <-- must exist
            ];


            Mail::to($siteEmail)->send(new TransferNotificationMail($details));
        }

        return response()->json(['success' => true, 'message' => 'Admin notified successfully for preview.']);
    }
}
