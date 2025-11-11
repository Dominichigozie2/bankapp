<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity; // <-- Add this

class UserProfileController extends Controller
{
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
            $path = $request->file('avatar')->store('avatars', 'public');

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $path;
        }

        $user->save();

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'profile',
            'description' => 'Updated profile information.',
        ]);

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
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (! Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'security',
            'description' => 'Changed account password.',
        ]);

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

        if ($user->passcode !== $request->old_passcode) {
            return response()->json([
                'success' => false,
                'message' => 'Old passcode is incorrect',
            ]);
        }

        $user->passcode = $request->new_passcode;
        $user->save();

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'security',
            'description' => 'Changed account passcode.',
        ]);

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

        // Log activity
        Activity::create([
            'user_id' => $user->id,
            'type' => 'kyc',
            'description' => 'Submitted KYC documents for verification.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KYC submitted successfully. Please wait for admin approval.',
        ]);
    }
}
