<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash; // add at top if not present
use Illuminate\Support\Facades\Crypt; // add at top if not present
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Card;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CardController extends Controller
{
   public function requestCard(Request $request)
{
    // 1) Validate input
    $v = Validator::make($request->all(), [
        'account_type' => 'required|string',
        'pin' => 'required|digits:6'
    ]);

    if ($v->fails()) {
        return response()->json([
            'success' => false,
            'message' => $v->errors()->first()
        ], 422);
    }

    $user = Auth::user();

    // 2) Check if user already has a card
    $existingCard = Card::where('user_id', $user->id)->first();
    if ($existingCard) {
        return response()->json([
            'success' => false,
            'message' => 'You have already requested a card. Please wait for admin approval.'
        ]);
    }

    // 3) Check passcode (PIN)
    $enteredPin = $request->pin;

    $pinMatches = false;
    if (password_needs_rehash($user->passcode ?? '', PASSWORD_DEFAULT)) {
        $pinMatches = ($enteredPin === ($user->passcode ?? ''));
    } else {
        try {
            $pinMatches = Hash::check($enteredPin, $user->passcode);
        } catch (\Exception $e) {
            $pinMatches = ($enteredPin === ($user->passcode ?? ''));
        }
    }

    if (! $pinMatches) {
        return response()->json([
            'success' => false,
            'message' => 'Incorrect account PIN (passcode).'
        ], 401);
    }

    // 4) Generate card details
    $fullCardNumber = '4' . random_int(100000000000000, 999999999999999);
    $last4 = substr($fullCardNumber, -4);
    $cvv = random_int(100, 999);
    $expiryMonth = str_pad((string) random_int(1, 12), 2, '0', STR_PAD_LEFT);
    $expiryYear = date('y', strtotime('+48 months')); // 4 years ahead
    $expiry = $expiryMonth . '/' . $expiryYear;

    $serial = strtoupper('SLB-' . Str::upper(Str::random(6)));
    $internetId = 'INT-' . strtoupper(Str::random(10));

    // 5) Store card in database (plain or encrypted)
    $card = Card::create([
        'user_id' => $user->id,
        'serial_key' => $serial,
        'internet_id' => $internetId,
        'card_number' => $fullCardNumber, // plain text (as you requested)
        'last4' => $last4,
        'card_name' => strtoupper($user->first_name . ' ' . $user->last_name),
        'card_expiration' => $expiry,
        'card_security' => (string)$cvv, // plain text CVV
        'payment_account' => $user->id . '-acct',
        'card_limit' => 0,
        'card_limit_remain' => 0,
        'card_status' => 2 // 2 = processing
    ]);

    // 6) Return response to frontend
    return response()->json([
        'success' => true,
        'message' => 'Card request submitted successfully. Please wait for admin approval.',
        'card' => [
            'serial_key' => $card->serial_key,
            'internet_id' => $card->internet_id,
            'card_number' => '**** **** **** ' . $card->last4,
            'last4' => $card->last4,
            'expiry' => $card->card_expiration,
            'holder_name' => $card->card_name,
        ]
    ]);
}

    
    public function showUserCardPage()
    {
        $card = Card::where('user_id', auth()->id())->first();
        return view('account.user.cards', compact('card'));
    }
}
