<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserCode;

class AdminUserCodeController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::latest()->get();
        return view('account.admin.user_codes', compact('users'));
    }

    // Fetch one user's codes (for modal)
    public function getUserCodes($id)
    {
        $user = User::findOrFail($id);
        $codes = UserCode::firstOrCreate(['user_id' => $user->id]);
        $codes->transfer_restricted = $user->transfer_restricted;
        return response()->json($codes);
    }

    // Update codes
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $codes = UserCode::firstOrCreate(['user_id' => $user->id]);
        $codes->update($request->only('cot_code', 'tax_code', 'imf_code'));

        $user->update([
            'transfer_restricted' => $request->has('transfer_restricted'),
        ]);

        return response()->json(['message' => 'Codes updated successfully.']);
    }
}
