<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
     public function index()
    {
        $user = Auth::user();
        return view('account.admin.profile', compact( 'user'));
    }
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name'  => 'required|string|max:60',
            'phone'      => 'nullable|string|max:20',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar'     => 'nullable|image|max:2048', // 2MB
        ]);

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->phone      = $request->phone;
        $user->email      = $request->email;

        if ($request->hasFile('avatar')) {
            // store the avatar in storage/app/public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');

            // optionally delete old avatar if exists and it was stored in public disk
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $path; // store the path like 'avatars/abc.jpg'
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar_url' => $user->avatar ? asset("storage/{$user->avatar}") : asset('assets/images/users/avatar-3.jpg'),
            ],
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // uses new_password_confirmation
        ]);

        if (! Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function updatePasscode(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'old_passcode' => 'required|string',
            'new_passcode' => 'required|string|min:4|confirmed',
        ]);

        // If you're using plain text (not hashed):
        if ($user->passcode !== $request->old_passcode) {
            return response()->json([
                'success' => false,
                'message' => 'Old passcode is incorrect',
            ]);
        }

        // Update and save
        $user->passcode = $request->new_passcode;
        $user->save(); // <-- this line actually commits it to DB

        return response()->json([
            'success' => true,
            'message' => 'Passcode changed successfully',
        ]);
    }

    public function submitKYC(Request $request)
    {
        $user = $request->user();

        if (in_array($user->kyc_status, ['pending', 'successful'])) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending or approved KYC submission.',
            ]);
        }

        $request->validate([
            'idfront' => 'required|image|max:2048',
            'idback' => 'required|image|max:2048',
            'id_no' => 'required|string|max:50',
            'addressproof' => 'required|image|max:2048',
        ]);

        $front = $request->file('idfront')->store('kyc/front', 'public');
        $back = $request->file('idback')->store('kyc/back', 'public');
        $address = $request->file('addressproof')->store('kyc/address', 'public');

        $user->idfront = $front;
        $user->idback = $back;
        $user->id_no = $request->id_no;
        $user->addressproof = $address;
        $user->kyc_status = 'pending';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'KYC submitted successfully. Please wait for admin approval.',
        ]);
    }
}
