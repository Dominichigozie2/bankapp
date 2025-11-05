<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Support\Facades\DB;

class AdminDepositController extends Controller
{
    /**
     * Show all deposits for admin
     */
    public function index()
    {
        $deposits = Deposit::with(['user', 'accountType', 'cryptoType'])
            ->latest()
            ->paginate(10);

        return view('account.admin.deposit', compact('deposits'));
    }

    /**
     * Approve a deposit (add to balance + account amount)
     */
    public function approve($id)
    {
        DB::beginTransaction();

        try {
            $deposit = Deposit::findOrFail($id);

            if ($deposit->status === 'approved') {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Deposit already approved.',
                ], 400);
            }

            // Update deposit status
            $deposit->status = 'approved';
            $deposit->save();

            // Update user's main balance
            $user = User::find($deposit->user_id);
            $user->balance += $deposit->amount;
            $user->save();

            // Update user's account balance (if account_type_id is set)
            if ($deposit->account_type_id) {
                $userAccount = UserAccount::where('user_id', $user->id)
                    ->where('account_type_id', $deposit->account_type_id)
                    ->first();

                if ($userAccount) {
                    $userAccount->account_amount += $deposit->amount;
                    $userAccount->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Deposit approved and user balance updated successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error approving deposit: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject a deposit
     */
    public function reject($id)
    {
        $deposit = Deposit::findOrFail($id);

        if ($deposit->status === 'approved') {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot reject an already approved deposit.',
            ], 400);
        }

        $deposit->status = 'rejected';
        $deposit->save();

        return response()->json([
            'status' => 'error',
            'message' => 'Deposit rejected successfully.',
        ]);
    }
}
