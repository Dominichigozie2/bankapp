<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminSetting;

class AdminSettingsController extends Controller
{
    public function edit()
    {
        $settings = AdminSetting::first();
        return view('account.admin.transfer_settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = AdminSetting::first();

        $validated = $request->validate([
            'service_charge' => 'required|numeric|min:0',
            'max_transfer_amount' => 'required|numeric|min:0',
            'transfer_success_message' => 'required|string|max:255',
            'global_cot_code' => 'nullable|string|max:255',
            'global_tax_code' => 'nullable|string|max:255',
            'global_imf_code' => 'nullable|string|max:255',
            'cot_message' => 'nullable|string',
            'tax_message' => 'nullable|string',
            'imf_message' => 'nullable|string',
            'transfer_instruction' => 'nullable|string',
            'deposit_instruction' => 'nullable|string',

            // ✅ Add new ones
            'cot_dep_message' => 'nullable|string',
            'tax_dep_message' => 'nullable|string',
            'imf_dep_message' => 'nullable|string',
        ]);

        // handle checkboxes
        $validated['cot_enabled'] = $request->has('cot_enabled');
        $validated['tax_enabled'] = $request->has('tax_enabled');
        $validated['imf_enabled'] = $request->has('imf_enabled');
        $validated['transfers_enabled'] = $request->has('transfers_enabled');

        // ✅ Update all fields
        $settings->update($validated);

        return response()->json(['success' => true, 'message' => 'Settings updated successfully!']);
    }
}
