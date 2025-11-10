<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CryptoType;
use Illuminate\Support\Str;

class CryptoTypeController extends Controller
{
    public function index()
    {
        $cryptos = CryptoType::latest()->get();
        return view('account.admin.crypto_types', compact('cryptos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'min_balance' => 'required|numeric|min:0',
            'wallet_address' => 'nullable|string|max:255',
        ]);

        $crypto = CryptoType::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'network' => $validated['network'],
            'description' => $validated['description'],
            'min_balance' => $validated['min_balance'],
            'wallet_address' => $validated['wallet_address'],
        ]);

        return response()->json(['success'=>true,'message'=>'Crypto created successfully','crypto'=>$crypto]);
    }

    public function show(CryptoType $cryptoType)
    {
        return response()->json($cryptoType);
    }

    public function update(Request $request, CryptoType $cryptoType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'min_balance' => 'required|numeric|min:0',
            'wallet_address' => 'nullable|string|max:255',
        ]);

        $cryptoType->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'network' => $validated['network'],
            'description' => $validated['description'],
            'min_balance' => $validated['min_balance'],
            'wallet_address' => $validated['wallet_address'],
        ]);

        return response()->json(['success'=>true,'message'=>'Crypto updated successfully','crypto'=>$cryptoType]);
    }

    public function destroy(CryptoType $cryptoType)
    {
        $cryptoType->delete();
        return response()->json(['success'=>true,'message'=>'Crypto deleted successfully']);
    }
}
