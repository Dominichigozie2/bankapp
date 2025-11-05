<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepositCode;
use App\Models\Setting;
use Illuminate\Support\Str;

class AdminDepositCodeController extends Controller
{
    // Show all codes + setting toggle
    public function index()
    {
        $codes = DepositCode::latest()->get();
        $isRequired = Setting::get('deposit_code_required', '0');

        return view('account.admin.depositcodes', compact('codes', 'isRequired'));
    }

    // Generate new code(s)
    public function generate(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:20',
        ]);

        for ($i = 0; $i < $request->quantity; $i++) {
            DepositCode::create([
                'code' => strtoupper(Str::random(10)),
                'status' => 'active',
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Codes generated successfully!']);
    }

    // Revoke a code
    public function revoke($id)
    {
        $code = DepositCode::findOrFail($id);
        $code->update(['status' => 'revoked']);

        return response()->json(['status' => 'success', 'message' => 'Code revoked successfully!']);
    }
}
