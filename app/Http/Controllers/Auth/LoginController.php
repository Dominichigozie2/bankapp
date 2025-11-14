<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Activity;
use App\Models\AdminSetting;
use App\Mail\UserLoggedIn; // We'll create this Mailable

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|string',
            'password'       => 'required|string',
        ]);

        // ✅ Lookup by current_account_number instead of email
        $user = User::where('current_account_number', $validated['account_number'])->first();

        // ❌ Invalid credentials
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid account number or password.',
            ], 401);
        }

        // 🚫 Check if user is banned
        if ($user->banned) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been restricted. Please contact customer service for assistance.',
            ], 403);
        }

        // ✅ If admin disabled passcode
        if (!$user->passcode_allow) {
            Auth::login($user);

            // ✅ Activity log
            Activity::create([
                'user_id' => $user->id,
                'type' => 'login',
                'description' => 'Logged into dashboard (passcode skipped).',
            ]);

            // ✅ Notify admin via site_email
            $siteEmail = AdminSetting::first()?->site_email;
            if ($siteEmail) {
                Mail::to($siteEmail)->send(new UserLoggedIn($user));
            }

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
            'account_number' => 'required|string',
            'passcode'       => 'required|string|min:6|max:6',
        ]);

        $user = User::where('current_account_number', $validated['account_number'])->first();

        if (!$user || $user->passcode !== $validated['passcode']) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect passcode.',
            ], 401);
        }

        if ($user->banned) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been restricted. Please contact customer service for assistance.',
            ], 403);
        }

        Auth::login($user);

        // ✅ Activity log
        Activity::create([
            'user_id' => $user->id,
            'type' => 'login',
            'description' => 'Logged into dashboard (passcode verified).',
        ]);

        // ✅ Notify admin
        $siteEmail = AdminSetting::first()?->site_email;
        if ($siteEmail) {
            Mail::to($siteEmail)->send(new UserLoggedIn($user));
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'redirect' => route('account.user.index'),
        ]);
    }

    public function savePasscode(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|string',
            'passcode'       => 'required|string|min:6|max:6',
        ]);

        $user = User::where('current_account_number', $validated['account_number'])->firstOrFail();

        if ($user->banned) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been restricted. Please contact customer service for assistance.',
            ], 403);
        }

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
