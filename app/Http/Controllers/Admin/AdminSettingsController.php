<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use App\Models\Transfer; // ✅ Add this line


class AdminSettingsController extends Controller
{
   public function edit()
{
    $settings = AdminSetting::first();
    $transfers = Transfer::with('user')->latest()->get(); // Load all transfers with user info

    return view('account.admin.transfer_settings', compact('settings', 'transfers'));
}


    public function update(Request $request)
    {
        $settings = AdminSetting::first();

        // Validate only numeric and text fields (booleans will be handled manually)
        $validated = $request->validate([
            'service_charge' => 'required|numeric|min:0',
            'max_transfer_amount' => 'required|numeric|min:0',
            'transfer_success_message' => 'required|string|max:255',
        ]);

        // Handle checkboxes safely (convert missing ones to false)
        $validated['cot_enabled'] = $request->has('cot_enabled');
        $validated['tax_enabled'] = $request->has('tax_enabled');
        $validated['imf_enabled'] = $request->has('imf_enabled');
        $validated['transfers_enabled'] = $request->has('transfers_enabled');

        // Update the existing record
        $settings->update($validated);

        return response()->json(['success' => true, 'message' => 'Settings updated successfully.']);
    }
}
