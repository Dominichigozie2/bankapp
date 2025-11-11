<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;
use App\Models\Transfer;
use App\Models\Loan;
use Illuminate\Support\Facades\Response;

class BankStatementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get filters from query
        $typeFilter = $request->query('type'); // Deposit, Transfer, Loan
        $dateFrom = $request->query('from');   // Y-m-d
        $dateTo = $request->query('to');       // Y-m-d

        // --- Fetch Deposits ---
        $deposits = Deposit::where('user_id', $user->id)
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($d) => [
                'type' => 'Deposit',
                'amount' => $d->amount,
                'status' => $d->status ?? 'pending',
                'created_at' => $d->created_at,
                'details' => "Deposit via {$d->method}" 
            ]);

        // --- Fetch Transfers ---
        $transfers = Transfer::where('user_id', $user->id)
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($t) => [
                'type' => ucfirst($t->type).' Transfer',
                'amount' => $t->amount,
                'status' => $t->status,
                'created_at' => $t->created_at,
                'details' => $t->details ?? "Reference: {$t->reference}"
            ]);

        // --- Fetch Loans ---
        $loans = Loan::where('user_id', $user->id)
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($l) => [
                'type' => 'Loan',
                'amount' => $l->amount,
                'status' => $l->status ?? 'pending',
                'created_at' => $l->created_at,
                'details' => "Loan ID: {$l->id}"
            ]);

        // Merge all transactions
        $transactions = $deposits->merge($transfers)->merge($loans);

        // Filter by type if requested
        if ($typeFilter) {
            $transactions = $transactions->filter(fn($tx) => strtolower($tx['type']) === strtolower($typeFilter));
        }

        // Sort by date descending
        $transactions = $transactions->sortByDesc('created_at')->values();

        // Paginate manually
        $perPage = 15;
        $currentPage = $request->query('page', 1);
        $paginatedTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $transactions->forPage($currentPage, $perPage),
            $transactions->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('account.user.bank_statement', [
            'transactions' => $paginatedTransactions,
            'typeFilter' => $typeFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }
public function download(Request $request)
{
    $query = collect();

    // Filter: Deposits
    $deposits = Deposit::query()
        ->selectRaw("'Deposit' as type, amount, proof_url as details, status, created_at")
        ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
        ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
        ->get();

    $query = $query->merge($deposits);

    // Filter: Transfers
    $transfers = Transfer::query()
        ->selectRaw("CASE 
                        WHEN type='self' THEN 'Self Transfer' 
                        WHEN type='local' THEN 'Local Transfer'
                        WHEN type='international' THEN 'International Transfer'
                     END as type,
                     amount, CONCAT(bank_name, ' - ', account_number) as details, status, created_at")
        ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
        ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
        ->get();

    $query = $query->merge($transfers);

    // Filter: Loans (if applicable)
    $loans = Loan::query()
        ->selectRaw("'Loan' as type, amount, CONCAT('Loan ID: ', id) as details, status, created_at")
        ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
        ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
        ->get();

    $query = $query->merge($loans);

    // Filter by Type
    if ($request->type) {
        $query = $query->filter(fn($tx) => $tx->type === $request->type);
    }

    // Sort by latest first
    $transactions = $query->sortByDesc('created_at');

    // CSV export
    $filename = 'bank_statement_' . now()->format('Y_m_d_H_i') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $columns = ['Time', 'Type', 'Details', 'Amount', 'Status'];

    $callback = function() use ($transactions, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($transactions as $tx) {
            $amountSign = in_array($tx->type, ['Deposit', 'Self Transfer']) ? '+' : '-';
            fputcsv($file, [
                \Carbon\Carbon::parse($tx->created_at)->format('d M Y, H:i'),
                $tx->type,
                $tx->details,
                $amountSign . number_format($tx->amount, 2),
                ucfirst($tx->status),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
