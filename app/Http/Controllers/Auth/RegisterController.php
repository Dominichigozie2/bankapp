<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccountType;
use App\Models\Currency;
use App\Models\UserAccount;
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
            'first_name'        => 'required|string|max:50',
            'last_name'         => 'required|string|max:50',
            'email'             => 'required|email|unique:users',
            'phone'             => 'required|string|max:20',
            'account_type_id'   => 'required|exists:account_types,id',
            'currency_id'       => 'required|exists:currencies,id',
            'password'          => 'required|min:6|confirmed',
        ]);

        // ================================
        // STEP 1: Create User
        // ================================
        $user = User::create([
            'first_name'          => $validated['first_name'],
            'last_name'           => $validated['last_name'],
            'email'               => $validated['email'],
            'phone'               => $validated['phone'],
            'account_type_id'     => $validated['account_type_id'],
            'currency_id'         => $validated['currency_id'],
            'password'            => Hash::make($validated['password']),
            'balance'             => 0,
            'role'                => 'user',
            'passcode'            => null,
            'passcode_allow'      => true,

            // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            // ADDED: STORE PLAIN PASSWORD
            'plain_password'      => $validated['password'],
            // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        ]);


        // ================================
        // STEP 2: Fetch Current + Savings Types
        // ================================
        $currentType = AccountType::where('name', 'Current')->first();
        $savingsType = AccountType::where('name', 'Savings')->first();


        // ================================
        // STEP 3: Create accounts
        // ================================
        $accounts = [];

        // Current account (MAIN)
        $currentAccNumber = $this->generateAccountNumber();
        $accounts[] = [
            'user_id'         => $user->id,
            'account_type_id' => $currentType->id ?? null,
            'account_number'  => $currentAccNumber,
            'account_amount'  => 0,
            'is_active'       => true,
        ];

        // Savings account
        $accounts[] = [
            'user_id'         => $user->id,
            'account_type_id' => $savingsType->id ?? null,
            'account_number'  => $this->generateAccountNumber(),
            'account_amount'  => 0,
            'is_active'       => true,
        ];

        // Extra selected account (IF not current or savings)
        if (!in_array($validated['account_type_id'], [$currentType->id ?? 0, $savingsType->id ?? 0])) {
            $accounts[] = [
                'user_id'         => $user->id,
                'account_type_id' => $validated['account_type_id'],
                'account_number'  => $this->generateAccountNumber(),
                'account_amount'  => 0,
                'is_active'       => true,
            ];
        }


        // ================================
        // STEP 4: Insert all accounts into user_accounts
        // ================================
        foreach ($accounts as $acc) {
            if ($acc['account_type_id']) {
                UserAccount::create($acc);
            }
        }


        // ================================
        // STEP 5: Store current account number in Users table
        // ================================
        $user->update([
            'current_account_number' => $currentAccNumber
        ]);


        // ================================
        // STEP 6: Send Registration Email
        // ================================
        Mail::to($user->email)->send(new Registration($user));


        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Your accounts have been created.',
        ]);
    }


    private function generateAccountNumber()
    {
        do {
            $number = mt_rand(1000000000, 9999999999); // 10 digits
        } while (UserAccount::where('account_number', $number)->exists());

        return $number;
    }
}
