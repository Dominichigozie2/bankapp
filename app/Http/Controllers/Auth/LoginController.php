<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        // ✅ If admin disabled passcode
        if (!$user->passcode_allow) {
            Auth::login($user);
            return response()->json([
                'success' => true,
                'message' => 'Login successful (passcode skipped).',
                'redirect' => route('account.user.index'),
            ]);
        }

        // ✅ Check if user has a passcode
        if (is_null($user->passcode)) {
            return response()->json([
                'first_time' => true,
                'message' => 'Please create your 6-digit passcode.',
            ]);
        } else {
            return response()->json([
                'require_passcode' => true,
                'message' => 'Enter your 6-digit passcode to continue.',
            ]);
        }
    }

    public function verifyPasscode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'passcode' => 'required|string|min:6|max:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || $user->passcode !== $validated['passcode']) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect passcode.',
            ], 401);
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'redirect' => route('account.user.index'),
        ]);
    }

    public function savePasscode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'passcode' => 'required|string|min:6|max:6',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();
        $user->passcode = $validated['passcode'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Passcode created successfully! You can now login.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
