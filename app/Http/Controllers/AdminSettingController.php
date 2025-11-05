<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingController extends Controller
{

  public function index()
{
    $depositCodeRequired = \App\Models\Setting::get('deposit_code_required', '0');
    return view('account.admin.settings', compact('depositCodeRequired'));
}


    public function depositCodeToggle(Request $request)
    {
        // Validate input
        $request->validate([
            'value' => 'required|boolean',
        ]);

        // Save setting to database
        Setting::updateOrCreate(
            ['key' => 'deposit_code_required'],
            ['value' => $request->value]
        );

        return response()->json(['status' => 'success', 'message' => 'Setting updated']);
    }
}
