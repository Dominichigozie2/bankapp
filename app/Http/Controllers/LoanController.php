<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Loan;
use App\Models\LoanLimit;
use App\Models\Activity; // <-- Added

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
        $existingActiveOrPending = Loan::where('user_id', $user->id)
            ->whereIn('status', [1, 2]) // 1=approved, 2=pending
            ->first();

        if ($existingActiveOrPending) {
            return response()->json(['success' => false, 'message' => 'You already have an active or pending loan.'], 400);
        }

        // Check loan limit
        $limit = LoanLimit::effectiveLimitFor($user->id);
        if (!is_null($limit) && (float)$request->amount > (float)$limit) {
            return response()->json(['success' => false, 'message' => "Request exceeds allowed loan limit ({$limit})."], 400);
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
            'description' => "Requested a loan of $" . number_format($request->amount, 2) . " (Type: {$request->loan_type})",
        ]);

        // Send loan confirmation email
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

        $limit = LoanLimit::where('user_id', $user->id)->value('limit_amount');
        if (is_null($limit)) $limit = LoanLimit::whereNull('user_id')->value('limit_amount');
        if (is_null($limit)) {
            return response()->json(['valid' => false, 'message' => 'No loan limit set. Please contact admin.']);
        }

        if ($request->amount > $limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Requested amount exceeds your loan limit of $' . number_format($limit, 2)
            ]);
        }

        return response()->json(['valid' => true, 'limit' => $limit]);
    }

    // 📧 Helper: Send loan request email
    protected function sendLoanRequestEmail($user, $loan)
    {
        $subject = '✅ Loan Request Submitted Successfully';
        $message = "
            <p>Dear {$user->first_name},</p>
            <p>Your loan request has been <strong>successfully submitted</strong> and is currently <strong>pending approval</strong>.</p>
            <p><strong>Loan Details:</strong></p>
            <ul>
                <li>Loan Type: {$loan->loan_type}</li>
                <li>Amount: $" . number_format($loan->amount, 2) . "</li>
                <li>Repayment Amount: $" . number_format($loan->repayment_amount, 2) . "</li>
                <li>Duration: {$loan->duration}</li>
                <li>Status: Pending Approval</li>
            </ul>
            <p>We’ll notify you once your loan has been reviewed.</p>
            <p>Best regards,<br>SpeedLight Bank</p>
        ";

        Mail::send([], [], function ($mail) use ($user, $subject, $message) {
            $mail->to($user->email)
                ->subject($subject)
                ->from('no-reply@speedlight-tech.com', 'SpeedLight Bank')
                ->html($message);
        });
    }

    // 💰 Repay active loan
    public function repayLoan($id)
    {
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);
        $user = Auth::user();

        if ($loan->status != 1) {
            return response()->json(['success' => false, 'message' => 'Only active loans can be repaid.']);
        }

        if ($user->balance < $loan->repayment_amount) {
            return response()->json(['success' => false, 'message' => 'Insufficient balance to repay this loan.']);
        }

        // Deduct repayment
        $user->balance -= $loan->repayment_amount;
        $user->save();

        $loan->update([
            'status' => 5, // paid
            'repaid_at' => now(),
        ]);

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'loan',
            'description' => "Repaid loan of $" . number_format($loan->repayment_amount, 2) . " (Loan ID: {$loan->id})",
        ]);

        // Optional: send email
        Mail::send([], [], function ($mail) use ($user, $loan) {
            $mail->to($user->email)
                ->subject('💰 Loan Repaid Successfully')
                ->from('no-reply@speedlight-tech.com', 'SpeedLight Bank')
                ->html("
                    <p>Dear {$user->first_name},</p>
                    <p>Your loan repayment of $" . number_format($loan->repayment_amount, 2) . " has been successfully processed.</p>
                ");
        });

        return response()->json(['success' => true, 'message' => 'Loan repaid successfully and deducted from your balance.']);
    }
}
