<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::latest()->get();
        return view('account.admin.currencies', compact('currencies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:currencies,code',
            'symbol' => 'nullable|string|max:10',
            'rate' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'errors'=>$validator->errors()]);
        }

        $currency = Currency::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'symbol' => $request->symbol,
            'rate' => $request->rate,
        ]);

        return response()->json(['success'=>true, 'currency'=>$currency]);
    }

    public function update(Request $request, Currency $currency)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:currencies,code,'.$currency->id,
            'symbol' => 'nullable|string|max:10',
            'rate' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false, 'errors'=>$validator->errors()]);
        }

        $currency->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'symbol' => $request->symbol,
            'rate' => $request->rate,
        ]);

        return response()->json(['success'=>true, 'currency'=>$currency]);
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return response()->json(['success'=>true]);
    }
}
