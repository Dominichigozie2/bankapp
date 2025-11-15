<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\Loan;
use App\Models\LoanLimit;
use App\Models\Activity;
use App\Models\UserAccount;

class LoanController extends Controller
{
    // 🧾 Show form and user's loans
    public function index()
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)->latest()->get();
        $limit = LoanLimit::effectiveLimitFor($user->id);

        return view('account.user.loan', compact('loans', 'limit'));
    }

    // 💳 Submit loan request
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

        // Prevent multiple active/pending loans
        $existing = Loan::where('user_id', $user->id)->whereIn('status', [1, 2])->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'You already have an active or pending loan.'], 400);
        }

        // Check loan limit
        $limit = LoanLimit::effectiveLimitFor($user->id);
        if (!is_null($limit) && (float)$request->amount > (float)$limit) {
            return response()->json(['success' => false, 'message' => "Request exceeds allowed loan limit ($limit)."], 400);
        }

        // Create the loan
        $loan = Loan::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'repayment_amount' => $request->repayment_amount,
            'loan_type' => $request->loan_type,
            'duration' => $request->duration,
            'bank_code' => $request->bank_code,
            'details' => $request->details,
            'status' => 2, // pending
        ]);

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'loan',
            'description' => "Requested a loan of $" . number_format($request->amount, 2),
        ]);

        // Send email
        $this->sendLoanRequestEmail($user, $loan);

        return response()->json(['success' => true, 'message' => 'Loan request submitted successfully.']);
    }

    // 🧾 Loan history
    public function history()
    {
        $user = Auth::user();
        $loans = Loan::where('user_id', $user->id)->latest()->get();
        return view('account.user.loanhistory', compact('loans'));
    }

    // 🔐 Validate passcode
    public function validatePasscode(Request $request)
    {
        $request->validate([
            'bank_code' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();

        if ($user->passcode !== $request->bank_code) {
            return response()->json(['valid' => false, 'message' => 'Incorrect account code.']);
        }

        $limit = LoanLimit::where('user_id', $user->id)->value('limit_amount')
              ?? LoanLimit::whereNull('user_id')->value('limit_amount');

        if (is_null($limit)) {
            return response()->json(['valid' => false, 'message' => 'No loan limit set. Contact admin.']);
        }

        if ($request->amount > $limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Requested amount exceeds loan limit: $' . number_format($limit, 2)
            ]);
        }

        return response()->json(['valid' => true, 'limit' => $limit]);
    }

    // 📧 Email function
    protected function sendLoanRequestEmail($user, $loan)
    {
        $subject = 'Loan Request Submitted';
        $message = "
            <p>Dear {$user->first_name},</p>
            <p>Your loan request has been submitted and is pending approval.</p>
        ";

        Mail::send([], [], function ($mail) use ($user, $subject, $message) {
            $mail->to($user->email)
                ->subject($subject)
                ->from('no-reply@speedlight-tech.com', 'SpeedLight Bank')
                ->html($message);
        });
    }

    // 💰 Repay active loan (DEDUCT ONLY FROM CURRENT ACCOUNT)
    public function repayLoan($id)
    {
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);
        $user = Auth::user();

        // Fetch current account properly
        $current = UserAccount::where('user_id', $user->id)
            ->whereHas('accountType', fn($q) => $q->where('name', 'Current'))
            ->first();

        if (!$current) {
            return response()->json([
                'success' => false,
                'message' => 'Current account not found.'
            ]);
        }

        if ($loan->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Only approved loans can be repaid.'
            ]);
        }

        if ($current->account_amount < $loan->repayment_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient funds in Current Account.'
            ]);
        }

        // Deduct repayment
        $current->account_amount -= $loan->repayment_amount;
        $current->save();

        // Mark loan as repaid
        $loan->update([
            'status' => 5,
            'repaid_at' => now(),
        ]);

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'loan',
            'description' => "Repaid loan of $" . number_format($loan->repayment_amount, 2) . " from Current Account"
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan repaid successfully from Current Account.'
        ]);
    }


}
