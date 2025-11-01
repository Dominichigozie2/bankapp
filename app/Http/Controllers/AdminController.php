<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function togglePasscode(User $user)
    {
        $user->passcode_allow = !$user->passcode_allow;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Passcode system ' . ($user->passcode_allow ? 'enabled' : 'disabled') . ' for user.'
        ]);
    }

    public function kycIndex()
    {
        $users = User::whereIn('kyc_status', ['pending', 'successful'])->get();

        return view('account.admin.kyc', compact('users'));
    }

    public function approveKYC($id)
    {
        $user = User::findOrFail($id);
        $user->update(['kyc_status' => 'successful']);

        return response()->json(['success' => true, 'message' => 'KYC approved successfully']);
    }

    public function rejectKYC($id)
    {
        $user = User::findOrFail($id);
        $user->update(['kyc_status' => 'empty']);

        return response()->json(['success' => true, 'message' => 'KYC rejected successfully']);
    }
}
