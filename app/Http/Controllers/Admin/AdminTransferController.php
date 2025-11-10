<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\AdminSetting;
use App\Models\UserAccount;
use Illuminate\Support\Facades\DB;

class AdminTransferController extends Controller
{
    public function index()
    {
        $settings = AdminSetting::first();
        $transfers = Transfer::with('user')->latest()->get();
        return view('account.admin.transfer', compact('transfers', 'settings'));
    }

    public function approve(Request $request, $id)
    {
        $transfer = Transfer::find($id);
        if (!$transfer) return response()->json(['success' => false, 'message' => 'Transfer not found.'], 404);
        if ($transfer->status !== 'pending') return response()->json(['success' => false, 'message' => 'Transfer already processed.'], 422);

        $user = $transfer->user;
        if (!$user) return response()->json(['success' => false, 'message' => 'User not found.'], 404);

        $settings = AdminSetting::first();
        $meta = is_string($transfer->meta) ? json_decode($transfer->meta, true) : ($transfer->meta ?? []);
        $serviceCharge = $meta['service_charge'] ?? ($settings->service_charge ?? 0);

        DB::beginTransaction();
        try {
            if ($transfer->type === 'self') {
                $fromAccount = UserAccount::find($meta['from_account'] ?? 0);
                $toAccount   = UserAccount::find($meta['to_account'] ?? 0);

                if (!$fromAccount || !$toAccount || $fromAccount->user_id !== $user->id || $toAccount->user_id !== $user->id) {
                    throw new \Exception('Invalid from/to account details.');
                }
                if ($fromAccount->account_amount < $transfer->amount) throw new \Exception('Insufficient funds in source account.');
                if ($user->balance < $serviceCharge) throw new \Exception('Insufficient balance to cover service charge.');

                $fromAccount->account_amount -= $transfer->amount;
                $fromAccount->save();

                $toAccount->account_amount += $transfer->amount;
                $toAccount->save();

                $user->balance -= floatval($serviceCharge);
                $user->save();
            } else {
                $fromAccount = UserAccount::find($meta['from_account'] ?? 0);
                if (!$fromAccount || $fromAccount->user_id !== $user->id) throw new \Exception('Invalid sender account.');
                if ($fromAccount->account_amount < $transfer->amount) throw new \Exception('Insufficient funds in source account.');
                if ($user->balance < $serviceCharge) throw new \Exception('Insufficient balance to cover service charge.');

                $fromAccount->account_amount -= $transfer->amount;
                $fromAccount->save();

                $user->balance -= floatval($serviceCharge);
                $user->save();
            }

            $transfer->update([
                'status' => 'success',
                'meta' => array_merge($meta, [
                    'approved_by' => auth()->id(),
                    'approved_at' => now()->toDateTimeString(),
                    'service_charge_applied' => $serviceCharge,
                ])
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transfer approved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $transfer = Transfer::find($id);
        if (!$transfer) return response()->json(['success' => false, 'message' => 'Transfer not found.'], 404);
        if ($transfer->status !== 'pending') return response()->json(['success' => false, 'message' => 'Transfer already processed.'], 422);

        $transfer->status = 'failed';
        $transfer->meta = array_merge(
            is_array($transfer->meta) ? $transfer->meta : (is_string($transfer->meta) ? json_decode($transfer->meta, true) : []),
            ['rejected_by' => auth()->id(), 'rejected_at' => now()->toDateTimeString()]
        );
        $transfer->save();

        return response()->json(['success' => true, 'message' => 'Transfer rejected.']);
    }
}
