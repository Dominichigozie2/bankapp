<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserCode;

class UserCodeController extends Controller
{
    // show a page where admin can search users and manage codes
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('account.admin.user_codes', compact('users'));
    }

    // show form to edit a single user's codes
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $userCodes = UserCode::firstOrNew(['user_id' => $user->id]);
        return view('account.admin.users.edit_user_codes', compact('user', 'userCodes'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'cot_code' => 'nullable|string|max:255',
            'tax_code' => 'nullable|string|max:255',
            'imf_code' => 'nullable|string|max:255',
            'transfer_restricted' => 'nullable|boolean'
        ]);

        $userCodes = UserCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'cot_code' => $validated['cot_code'] ?? null,
                'tax_code' => $validated['tax_code'] ?? null,
                'imf_code' => $validated['imf_code'] ?? null,
            ]
        );

        $user->transfer_restricted = (bool) $request->input('transfer_restricted', false);
        $user->save();

        return redirect()->route('admin.users.codes')->with('success', 'User codes updated.');
    }
}
