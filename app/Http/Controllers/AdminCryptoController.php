<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CryptoType;

class AdminCryptoController extends Controller
{
    public function index()
    {
        $cryptos = CryptoType::latest()->paginate(20);
        return view('account.admin.crypto.index', compact('cryptos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'slug'=>'nullable|string|unique:crypto_types,slug',
            'network'=>'nullable|string',
            'wallet_address'=>'nullable|string',
            'min_balance'=>'nullable|numeric',
        ]);

        CryptoType::create($request->only(['name','slug','network','wallet_address','min_balance','description']));
        return back()->with('success','Crypto added');
    }

    public function update(Request $request, CryptoType $crypto)
    {
        $crypto->update($request->only(['name','slug','network','wallet_address','min_balance','description']));
        return back()->with('success','Crypto updated');
    }
}
