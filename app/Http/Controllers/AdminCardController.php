<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;

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
        return response()->json(['success' => true, 'message' => 'Card approved successfully.']);
    }

    // ⏸️ Hold
    public function hold($id)
    {
        $card = Card::findOrFail($id);
        $card->update(['card_status' => 3]);
        return response()->json(['success' => true, 'message' => 'Card placed on hold.']);
    }

    // ❌ Reject
    public function reject($id)
    {
        $card = Card::findOrFail($id);
        $card->update(['card_status' => 0]);
        return response()->json(['success' => true, 'message' => 'Card rejected successfully.']);
    }
}
