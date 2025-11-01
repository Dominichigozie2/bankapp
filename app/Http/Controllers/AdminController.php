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

}
