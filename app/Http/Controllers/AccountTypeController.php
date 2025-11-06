<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountType;
use Illuminate\Support\Str;

class AccountTypeController extends Controller
{
    public function index()
    {
        $types = AccountType::latest()->paginate(10);
        return view('account.admin.accounttypes', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:account_types,name',
            'description' => 'nullable|string',
            'min_balance' => 'nullable|numeric|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        AccountType::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Account type added successfully!',
        ]);
    }

    public function delete($id)
    {
        $type = AccountType::findOrFail($id);
        $type->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account type deleted successfully!',
        ]);
    }
}
