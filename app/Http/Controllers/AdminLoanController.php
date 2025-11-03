<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\LoanLimit;
use Illuminate\Support\Facades\Auth;

class AdminLoanController extends Controller
{
    // 🧾 List all loans for admin
    public function index()
    {
        $loans = Loan::with('user')->latest()->get();
        return view('account.admin.loan', compact('loans'));
    }

    // ✅ Approve or Resume Loan
    public function approve(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $user = $loan->user;

        // Parse duration text (like "2 weeks" or "3 months")
        $duration = strtolower(trim($loan->duration));
        $dueDate = now();

        preg_match('/(\d+)/', $duration, $match);
        $number = isset($match[1]) ? (int)$match[1] : 0;

        if (str_contains($duration, 'day')) {
            $dueDate = now()->addDays($number ?: 1);
        } elseif (str_contains($duration, 'week')) {
            $dueDate = now()->addWeeks($number ?: 1);
        } elseif (str_contains($duration, 'month')) {
            $dueDate = now()->addMonths($number ?: 1);
        } elseif (str_contains($duration, 'year')) {
            $dueDate = now()->addYears($number ?: 1);
        } else {
            $dueDate = now()->addDays(7);
        }

        $approvedAmount = $request->approved_amount ?? $loan->amount;

        // 🧠 Case 1: Already Approved
        if ($loan->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Loan already approved previously.'
            ]);
        }

        // 🧠 Case 2: If Loan was on Hold
        if ($loan->status == 3) {
            $loan->update([
                'status' => 1,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'due_date' => $dueDate,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Loan resumed successfully.'
            ]);
        }

        // 🧠 Case 3: Normal approval (first time)
        $loan->update([
            'status' => 1,
            'approved_amount' => $approvedAmount,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'due_date' => $dueDate,
        ]);

        // ✅ Credit user once only
        $user->balance = $user->balance + $approvedAmount;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Loan approved and credited to user balance.'
        ]);
    }

    // ⏸ Hold Loan
    public function hold($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Only active loans can be placed on hold.'
            ]);
        }

        $loan->update(['status' => 3]);

        return response()->json([
            'success' => true,
            'message' => 'Loan placed on hold.'
        ]);
    }

    // ❌ Reject Loan
    public function reject($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot reject an already approved loan.'
            ]);
        }

        $loan->update(['status' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Loan rejected.'
        ]);
    }

    // 💰 Mark Loan as Paid Back
    public function markAsPaid($id)
    {
        $loan = Loan::findOrFail($id);
        $user = $loan->user;

        if ($loan->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Only active loans can be marked as paid.'
            ]);
        }

        // Deduct the loan amount from user balance
        $user->balance = max(0, $user->balance - $loan->approved_amount);
        $user->save();

        // Mark loan as completed
        $loan->update([
            'status' => 5, // 5 = paid
            'due_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan marked as paid and deducted from user balance.'
        ]);
    }

    // 🕒 Mark Overdue Loans Automatically
    public function markDueLoans()
    {
        $dueLoans = Loan::where('status', 1)
            ->whereDate('due_date', '<', now())
            ->get();

        foreach ($dueLoans as $loan) {
            $loan->update(['status' => 4]); // 4 = due
        }

        return response()->json([
            'success' => true,
            'message' => 'All overdue loans have been marked as due.'
        ]);
    }

    // ⚙️ Set Loan Limit
    public function setLimit(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'limit_amount' => 'required|numeric|min:0',
        ]);

        LoanLimit::updateOrCreate(
            ['user_id' => $data['user_id']],
            ['limit_amount' => $data['limit_amount']]
        );

        return response()->json([
            'success' => true,
            'message' => 'Loan limit set successfully.'
        ]);
    }
}
