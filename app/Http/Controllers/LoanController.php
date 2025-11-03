<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Loan;
use App\Models\LoanLimit;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    // show form and user's loans
    public function index()
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)->latest()->get();
        $limit = LoanLimit::effectiveLimitFor($user->id);

        return view('account.user.loan', compact('loans', 'limit'));
    }

    // submit loan request
    public function requestLoan(Request $request)
    {
        $v = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'repayment_amount' => 'required|numeric|min:1',
            'loan_type' => 'required|string',
            'duration' => 'required|string',
            'bank_code' => 'required|string',
            'details' => 'nullable|string',
        ]);

        if ($v->fails()) {
            return response()->json(['success' => false, 'message' => $v->errors()->first()], 422);
        }

        $user = Auth::user();

        // ✅ Prevent multiple active/pending loans
        $existingActiveOrPending = Loan::where('user_id', $user->id)
            ->whereIn('status', [1, 2]) // 1=approved, 2=pending
            ->first();

        if ($existingActiveOrPending) {
            return response()->json(['success' => false, 'message' => 'You already have an active or pending loan.'], 400);
        }

        // ✅ Check loan limit
        $limit = LoanLimit::effectiveLimitFor($user->id);
        if (!is_null($limit) && (float)$request->amount > (float)$limit) {
            return response()->json(['success' => false, 'message' => "Request exceeds allowed loan limit ({$limit})."], 400);
        }

        // ✅ Create the loan
        Loan::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'repayment_amount' => $request->repayment_amount,
            'loan_type' => $request->loan_type,
            'duration' => $request->duration,
            'bank_code' => $request->bank_code,
            'details' => $request->details,
            'status' => 2, // pending
        ]);

        return response()->json(['success' => true, 'message' => 'Loan request submitted successfully.']);
    }

    public function history()
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)->latest()->get();
        return view('account.user.loanhistory', compact('loans'));
    }

    public function validatePasscode(Request $request)
    {
        $request->validate([
            'bank_code' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();

        // Check passcode
        if ($user->passcode !== $request->bank_code) {
            return response()->json([
                'valid' => false,
                'message' => 'Incorrect account code.'
            ]);
        }

        // Fetch loan limit (user-specific or global)
        $limit = LoanLimit::where('user_id', $user->id)->value('limit_amount');
        if (is_null($limit)) {
            $limit = LoanLimit::whereNull('user_id')->value('limit_amount'); // global
        }

        // If no limit found, reject request
        if (is_null($limit)) {
            return response()->json([
                'valid' => false,
                'message' => 'No loan limit set. Please contact admin.'
            ]);
        }

        // Check against limit
        if ($request->amount > $limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Requested amount exceeds your loan limit of $' . number_format($limit, 2)
            ]);
        }

        return response()->json(['valid' => true, 'limit' => $limit]);
    }
}
