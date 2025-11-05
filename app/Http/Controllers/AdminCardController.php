<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdminCardController extends Controller
{
    // 🧾 List all cards
    public function index()
    {
        $cards = Card::with('user')->latest()->get();
        return view('account.admin.cards', compact('cards'));
    }

    // ✅ Approve
    public function approve($id)
    {
        $card = Card::findOrFail($id);
        $card->update(['card_status' => 1]);

        // send email
        $this->sendCardEmail($card->user, 'approved', $card);

        return response()->json(['success' => true, 'message' => 'Card approved successfully.']);
    }

    // ⏸️ Hold
    public function hold($id)
    {
        $card = Card::findOrFail($id);
        $card->update(['card_status' => 3]);

        // send email
        $this->sendCardEmail($card->user, 'hold', $card);

        return response()->json(['success' => true, 'message' => 'Card placed on hold.']);
    }

    // ❌ Reject
    public function reject($id)
    {
        $card = Card::findOrFail($id);
        $card->update(['card_status' => 0]);

        // send email
        $this->sendCardEmail($card->user, 'rejected', $card);

        return response()->json(['success' => true, 'message' => 'Card rejected successfully.']);
    }

    // 📧 Helper to send card email
    protected function sendCardEmail($user, $type, $card)
    {
        $subject = '';
        $message = '';

        if ($type === 'approved') {
            $subject = '🎉 Your Card Has Been Approved!';
            $message = "
                <p>Dear {$user->first_name},</p>
                <p>Your debit card has been <strong>approved</strong> and is now active.</p>
                <p><strong>Card Details:</strong></p>
                <ul>
                    <li>Card Number: **** **** **** " . substr($card->card_number, -4) . "</li>
                    <li>Card Type: Debit Card</li>
                    <li>Expiration: {$card->card_expiration}</li>
                </ul>
                <p>You can now view your card from your dashboard.</p>
                <p>Best regards,<br>SpeedLight Bank</p>
            ";
        } elseif ($type === 'hold') {
            $subject = '⚠️ Your Card is on Hold';
            $message = "
                <p>Dear {$user->first_name},</p>
                <p>Your debit card request has been placed <strong>on hold</strong>. This could be due to verification issues or missing details.</p>
                <p>Please contact customer support for assistance.</p>
                <p>Best regards,<br>SpeedLight Bank</p>
            ";
        } elseif ($type === 'rejected') {
            $subject = '❌ Your Card Request Has Been Rejected';
            $message = "
                <p>Dear {$user->first_name},</p>
                <p>We regret to inform you that your debit card request was <strong>rejected</strong>.</p>
                <p>Please review your information and try again or contact support for more details.</p>
                <p>Best regards,<br>SpeedLight Bank</p>
            ";
        }

        Mail::send([], [], function ($mail) use ($user, $subject, $message) {
            $mail->to($user->email)
                ->subject($subject)
                ->from('no-reply@speedlight-tech.com', 'SpeedLight Bank')
                ->html("
                    <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:20px;border:1px solid #eee;border-radius:10px;'>
                        <div style='text-align:center;'>
                            <img src='" . asset('assets/images/logo-sm.svg') . "' alt='Logo' width='60'>
                            <h2 style='color:#4f46e5;'>SpeedLight Bank</h2>
                        </div>
                        <div style='margin-top:20px;color:#333;font-size:15px;'>
                            {$message}
                        </div>
                        <div style='margin-top:30px;text-align:center;color:#999;font-size:13px;'>
                            <p>© " . date('Y') . " SpeedLight Bank. All rights reserved.</p>
                        </div>
                    </div>
                ");
        });
    }
}
