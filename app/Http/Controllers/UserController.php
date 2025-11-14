<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a paginated list of users with search.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('account.admin.user', compact('users'));
    }

    /**
     * Show a single user as JSON (for modal).
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Add a new user via AJAX.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'nullable|string|max:20',
            'password'         => 'required|min:6',
            'passcode'         => 'nullable|string|max:10',
            'role'             => 'required|string',
            'account_type_id'  => 'nullable|exists:account_types,id',
            'currency_id'      => 'nullable|exists:currencies,id',
        ]);

        $user = User::create([
            'user_id'          => 'U' . time() . rand(100, 999), // generate unique user_id
            'first_name'       => $validated['first_name'],
            'last_name'        => $validated['last_name'],
            'email'            => $validated['email'],
            'phone'            => $validated['phone'] ?? null,
            'password'         => Hash::make($validated['password']),
            'passcode'         => $validated['passcode'] ?? null,
            'role'             => $validated['role'],
            'account_type_id'  => $validated['account_type_id'] ?? null,
            'currency_id'      => $validated['currency_id'] ?? null,
            'balance'          => 0,
            'last_seen'        => now(),
            // ADDED: STORE PLAIN PASSWORD
            'plain_password'      => $validated['password'],
            // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User added successfully',
            'user'    => $user
        ]);
    }



    /**
     * Toggle user verification status.
     */
    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->verified = !$user->verified;
        $user->save();

        return response()->json(['success' => true, 'status' => $user->verified]);
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }

    public function toggleBan($id)
    {
        $user = User::findOrFail($id);
        $user->banned = !$user->banned;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->banned
                ? 'User has been banned successfully.'
                : 'User has been unbanned successfully.',
            'status' => $user->banned,
        ]);
    }
}
