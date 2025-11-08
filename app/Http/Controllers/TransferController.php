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

    // LOCAL TRANSFER
    public function storeLocal(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        if (!$settings->transfers_enabled) {
            return response()->json(['success'=>false,'message'=>'Transfers are temporarily disabled.'],403);
        }

        $validated = $request->validate([
            'amount'=>'required|numeric|min:0.01',
            'account'=>'required|exists:user_accounts,id',
            'bank_name'=>'required|string',
            'account_number'=>'required|string',
            'account_name'=>'required|string',
            'details'=>'nullable|string',
            'passcode'=>'required|string'
        ]);

        if($user->passcode !== $validated['passcode']){
            return response()->json(['success'=>false,'message'=>'Incorrect account passcode'],401);
        }

        if($user->transfer_restricted){
            return response()->json(['success'=>false,'message'=>'Your transfer access is restricted.'],403);
        }

        if($validated['amount'] > $settings->max_transfer_amount){
            return response()->json(['success'=>false,'message'=>'Amount exceeds maximum transfer limit.'],422);
        }

        $fromAccount = UserAccount::find($validated['account']);

        $transfer = Transfer::create([
            'user_id' => $user->id,
            'type' => 'local',
            'amount' => $validated['amount'],
            'bank_name' => $validated['bank_name'] ?? $fromAccount->bank_name,
            'account_number' => $validated['account_number'] ?? $fromAccount->account_number,
            'account_name' => $validated['account_name'] ?? $fromAccount->account_name,
            'details' => $validated['details'] ?? null,
            'reference' => strtoupper(Str::random(10)),
            'status' => 'pending',
            'meta' => ['service_charge' => $settings->service_charge],
        ]);

        return response()->json([
            'success'=>true,
            'message'=>$settings->transfer_success_message,
            'redirect'=>route('user.transfers.history')
        ]);
    }

    // INTERNATIONAL TRANSFER
    public function storeInternational(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();

        $validated = $request->validate([
            'amount'=>'required|numeric|min:0.01',
            'account'=>'required|exists:user_accounts,id',
            'bank_name'=>'required|string',
            'account_number'=>'required|string',
            'account_name'=>'required|string',
            'bank_country'=>'required|string',
            'routine_number'=>'nullable|string',
            'bank_code'=>'nullable|string',
            'details'=>'nullable|string',
            'passcode'=>'required|string',
            'cot_code'=>'nullable|string',
            'tax_code'=>'nullable|string',
            'imf_code'=>'nullable|string',
        ]);

        if (!$settings->transfers_enabled || $user->transfer_restricted) {
            return response()->json(['success'=>false,'message'=>'Transfers are temporarily disabled or restricted.'],403);
        }

        if($validated['amount'] > $settings->max_transfer_amount){
            return response()->json(['success'=>false,'message'=>'Amount exceeds maximum transfer limit.'],422);
        }

        // Verify codes
        $userCodes = UserCode::firstOrNew(['user_id'=>$user->id]);

        if($settings->cot_enabled && strtoupper(trim($validated['cot_code'])) !== strtoupper(trim($userCodes->cot_code))){
            return response()->json(['success'=>false,'message'=>'Invalid COT code.'],401);
        }
        if($settings->tax_enabled && strtoupper(trim($validated['tax_code'])) !== strtoupper(trim($userCodes->tax_code))){
            return response()->json(['success'=>false,'message'=>'Invalid TAX code.'],401);
        }
        if($settings->imf_enabled && strtoupper(trim($validated['imf_code'])) !== strtoupper(trim($userCodes->imf_code))){
            return response()->json(['success'=>false,'message'=>'Invalid IMF code.'],401);
        }

        if($user->passcode !== $validated['passcode']){
            return response()->json(['success'=>false,'message'=>'Incorrect account passcode.'],401);
        }

        $fromAccount = UserAccount::find($validated['account']);

        $transfer = Transfer::create([
            'user_id'=>$user->id,
            'type'=>'international',
            'amount'=>$validated['amount'],
            'bank_name'=>$validated['bank_name'] ?? $fromAccount->bank_name,
            'account_number'=>$validated['account_number'] ?? $fromAccount->account_number,
            'account_name'=>$validated['account_name'] ?? $fromAccount->account_name,
            'bank_country'=>$validated['bank_country'],
            'routine_number'=>$validated['routine_number'] ?? null,
            'bank_code'=>$validated['bank_code'] ?? null,
            'details'=>$validated['details'] ?? null,
            'reference'=>strtoupper(Str::random(10)),
            'status'=>'pending',
            'meta'=>[
                'cot_code'=>$validated['cot_code'] ?? null,
                'tax_code'=>$validated['tax_code'] ?? null,
                'imf_code'=>$validated['imf_code'] ?? null,
                'service_charge'=>$settings->service_charge
            ],
        ]);

        return response()->json([
            'success'=>true,
            'message'=>$settings->transfer_success_message,
            'redirect'=>route('user.transfers.history')
        ]);
    }

    // SELF TRANSFER
    public function selfTransfer(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount'=>'required|numeric|min:1',
            'from_account'=>'required|different:to_account',
            'to_account'=>'required',
            'passcode'=>'required|min:6|max:6',
        ]);

        if($request->passcode !== $user->passcode){
            return response()->json(['success'=>false,'message'=>'Incorrect account passcode']);
        }

        $fromAccount = UserAccount::where('id',$request->from_account)->where('user_id',$user->id)->first();
        if(!$fromAccount) return response()->json(['success'=>false,'message'=>'Invalid source account']);

        $toAccount = UserAccount::where('id',$request->to_account)->where('user_id',$user->id)->first();
        if(!$toAccount) return response()->json(['success'=>false,'message'=>'Invalid destination account']);

        $transfer = Transfer::create([
            'user_id'=>$user->id,
            'type'=>'self',
            'amount'=>$request->amount,
            'bank_name'=>'Internal Transfer',
            'account_name'=>$toAccount->account_name ?: $user->name,
            'account_number'=>$fromAccount->account_number,
            'details'=>"Transfer from Account #{$fromAccount->id} to Account #{$toAccount->id}",
            'reference'=>strtoupper(Str::random(10)),
            'status'=>'pending',
            'meta'=>[
                'from_account'=>$fromAccount->id,
                'to_account'=>$toAccount->id
            ],
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Self transfer recorded successfully! Pending admin confirmation.',
            'transfer_id'=>$transfer->id
        ]);
    }

    // VERIFY CODES (AJAX)
    public function verifyCodes(Request $request)
    {
        $user = Auth::user();
        $settings = AdminSetting::first();
        $userCodes = UserCode::firstOrNew(['user_id'=>$user->id]);

        $errors = [];
        $valid = true;

        $normalize = fn($v)=> $v ? strtoupper(trim($v)) : null;

        $cotIn = $normalize($request->cot_code);
        $taxIn = $normalize($request->tax_code);
        $imfIn = $normalize($request->imf_code);

        $cotStored = $normalize($userCodes->cot_code);
        $taxStored = $normalize($userCodes->tax_code);
        $imfStored = $normalize($userCodes->imf_code);

        if($settings->cot_enabled && $cotIn !== $cotStored){ $errors['cot']='Invalid COT code'; $valid=false; }
        if($settings->tax_enabled && $taxIn !== $taxStored){ $errors['tax']='Invalid TAX code'; $valid=false; }
        if($settings->imf_enabled && $imfIn !== $imfStored){ $errors['imf']='Invalid IMF code'; $valid=false; }

        if(!$valid){
            return response()->json(['success'=>false,'message'=>'One or more codes are incorrect','errors'=>$errors],422);
        }

        return response()->json(['success'=>true,'message'=>'All required codes verified successfully.']);
    }

    public function history()
    {
        $user = Auth::user();
        $transfers = Transfer::where('user_id',$user->id)->latest()->paginate(10);
        return view('account.user.transfer_history', compact('transfers'));
    }

    public function invoice($id)
    {
        $user = Auth::user();
        $transfer = Transfer::where('id',$id)->where('user_id',$user->id)->firstOrFail();
        return view('account.user.transfer_invoice', compact('transfer'));
    }
}
