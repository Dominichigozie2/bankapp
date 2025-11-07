<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\User;
use App\Models\AdminSetting;
use Illuminate\Support\Facades\DB;

class AdminTransferController extends Controller
{
    // Show admin transfers page
    public function index()
    {
        // load settings to show service charge or other info if needed in view
        $settings = AdminSetting::first();
        // eager-load user for each transfer
        $transfers = Transfer::with('user')->latest()->get();

        return view('account.admin.transfer', compact('transfers', 'settings'));
    }

    // Approve transfer (ajax)
    public function approve(Request $request, $id)
    {
        $transfer = Transfer::find($id);
        if (! $transfer) {
            return response()->json(['success' => false, 'message' => 'Transfer not found.'], 404);
        }

        if ($transfer->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Transfer already processed.'], 422);
        }

        $user = $transfer->user;
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Determine service charge (meta or settings)
        $meta = is_string($transfer->meta) ? json_decode($transfer->meta, true) : ($transfer->meta ?? []);
        $serviceCharge = $meta['service_charge'] ?? (AdminSetting::first()->service_charge ?? 0);

        // compute total deduction (you can change business logic here)
        $totalDeduction = floatval($transfer->amount) + floatval($serviceCharge);

        DB::beginTransaction();
        try {
            // ensure user has enough balance
            if (floatval($user->balance) < $totalDeduction) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Insufficient user balance.'], 422);
            }

            // deduct from user balance
            $user->balance = floatval($user->balance) - $totalDeduction;
            $user->save();

            // mark transfer success and store approved meta (optional)
            $transfer->status = 'success';
            $transfer->meta = array_merge(is_array($meta) ? $meta : [], [
                'approved_by' => auth()->id(),
                'approved_at' => now()->toDateTimeString(),
                'service_charge_applied' => $serviceCharge,
            ]);
            // ensure JSON saved if model casts to array/json
            $transfer->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transfer approved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while approving transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reject transfer (admin action)
    public function reject(Request $request, $id)
    {
        $transfer = Transfer::find($id);
        if (! $transfer) {
            return response()->json(['success' => false, 'message' => 'Transfer not found.'], 404);
        }

        if ($transfer->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Transfer already processed.'], 422);
        }

        $transfer->status = 'failed';
        $transfer->meta = array_merge(
            is_array($transfer->meta) ? $transfer->meta : (is_string($transfer->meta) ? json_decode($transfer->meta, true) : []),
            ['rejected_by' => auth()->id(), 'rejected_at' => now()->toDateTimeString()]
        );
        $transfer->save();

        return response()->json(['success' => true, 'message' => 'Transfer rejected.']);
    }
}
