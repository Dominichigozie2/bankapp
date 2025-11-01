<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccountType;
use App\Models\Currency;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Registration;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        $accountTypes = AccountType::all();
        $currencies = Currency::all();

        return view('auth.register', compact('accountTypes', 'currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'account_type_id' => 'required|exists:account_types,id',
            'currency_id' => 'required|exists:currencies,id',
            'password' => 'required|min:6|confirmed',
        ]);
        // Generate random unique account numbers
        $currentAccountNumber = $this->generateAccountNumber();
        $savingsAccountNumber = $this->generateAccountNumber();

        // Create user
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'account_type_id' => $validated['account_type_id'], // ✅ fixed
            'currency_id' => $validated['currency_id'],        // ✅ fixed
            'password' => Hash::make($validated['password']),
            'balance' => 0,
            'role' => 'user',
            'current_account_number' => $currentAccountNumber,
            'savings_account_number' => $savingsAccountNumber,
            'passcode' => null, // Ensure it's null for first login tracking
            'passcode_allow' => true,
        ]);

        // Send email with passcode
        Mail::to($user->email)->send(new Registration($user->first_name, $currentAccountNumber));
        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Check your email for your passcode.',
        ]);
    }

    private function generateAccountNumber()
    {
        do {
            $number = mt_rand(1000000000, 9999999999); // 10 digits
        } while (User::where('current_account_number', $number)
            ->orWhere('savings_account_number', $number)
            ->exists()
        );
    
        return $number;
    }
}
