<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Filters
        $dateFrom = $request->from;
        $dateTo   = $request->to;
        $typeFilter = $request->type;

        // Query
        $query = Activity::where('user_id', $user->id)
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->when($typeFilter, fn($q) => $q->where('type', $typeFilter))
            ->orderByDesc('created_at');

        $activities = $query->paginate(15)->withQueryString();

        return view('account.user.activities', compact('activities', 'dateFrom', 'dateTo', 'typeFilter'));
    }
}
