<?php

namespace App\Http\Controllers;
use App\Mail\CardRequestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Card;


class CardController extends Controller
{
    // 🪪 User requests a new card
    public function requestCard(Request $request)
    {
        $v = Validator::make($request->all(), [
            'account_type' => 'required|string',
            'pin' => 'required|digits:6',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => $v->errors()->first(),
            ], 422);
        }

        $user = Auth::user();

        // Check if user already has a card
        $existingCard = Card::where('user_id', $user->id)->first();
        if ($existingCard) {
            return response()->json([
                'success' => false,
                'message' => 'You have already requested a card. Please wait for admin approval.',
            ]);
        }

        // ✅ Compare user-entered pin to stored passcode
        if ($request->pin !== $user->passcode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid passcode',
            ], 400);
        }

        // Generate card details
        $fullCardNumber = '4' . random_int(100000000000000, 999999999999999);
        $last4 = substr($fullCardNumber, -4);
        $cvv = random_int(100, 999);
        $expiryMonth = str_pad(random_int(1, 12), 2, '0', STR_PAD_LEFT);
        $expiryYear = date('y', strtotime('+4 years'));
        $expiry = "{$expiryMonth}/{$expiryYear}";

        $serial = 'SLB-' . strtoupper(Str::random(6));
        $internetId = 'INT-' . strtoupper(Str::random(10));

        // Store card
        // Store card
        $card = Card::create([
            'user_id' => $user->id,
            'serial_key' => $serial,
            'internet_id' => $internetId,
            'card_number' => $fullCardNumber,
            'last4' => $last4,
            'card_name' => strtoupper("{$user->first_name} {$user->last_name}"),
            'card_expiration' => $expiry,
            'card_security' => $cvv,
            'payment_account' => $user->id . '-acct',
            'card_limit' => 0,
            'card_limit_remain' => 0,
            'card_status' => 2, // pending
        ]);

        // ✅ Send email notification
        Mail::to($user->email)->send(new CardRequestMail($user, $card));


        return response()->json([
            'success' => true,
            'message' => 'Card request submitted successfully. Please wait for admin approval.',
        ]);
    }

    public function showUserCardPage()
    {
        $card = Card::where('user_id', Auth::id())->first();
        return view('account.user.cards', compact('card'));
    }

    public function deactivate(Request $request)
{
    $user = auth()->user();
    $card = $user->card;

    if (!$card || $card->card_status != 1) {
        return response()->json(['success' => false, 'message' => 'No active card found.']);
    }

    $card->card_status = 3; // on hold or deactivated
    $card->save();

    return response()->json(['success' => true, 'message' => 'Your card has been deactivated successfully.']);
}


}
