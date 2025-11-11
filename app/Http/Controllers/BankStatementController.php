<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deposit;
use App\Models\Transfer;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ Correct PDF import
use Illuminate\Pagination\LengthAwarePaginator;

class BankStatementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $typeFilter = $request->query('type'); // Deposit, Transfer, Loan
        $dateFrom = $request->query('from');   
        $dateTo = $request->query('to');       

        // --- Deposits ---
        $deposits = Deposit::where('user_id', $user->id)
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'type' => 'Deposit',
                'amount' => $d->amount,
                'status' => $d->status ? 'Successful' : 'Pending',
                'created_at' => $d->created_at,
                'details' => "Deposit via {$d->method}",
                'proof_url' => $d->proof_url,
            ]);

        // --- Transfers ---
        $transfers = Transfer::where('user_id', $user->id)
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'type' => ucfirst($t->type) . ' Transfer',
                'amount' => $t->amount,
                'status' => $t->status ? 'Successful' : 'Pending',
                'created_at' => $t->created_at,
                'details' => $t->details ?? "Reference: {$t->reference}",
            ]);

        // --- Loans ---
        $loans = Loan::where('user_id', $user->id)
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->get()
            ->map(fn($l) => [
                'id' => $l->id,
                'type' => 'Loan',
                'amount' => $l->amount,
                'status' => $l->status == 1 ? 'Successful' : 'Pending',
                'created_at' => $l->created_at,
                'details' => "Loan ID: {$l->id}",
            ]);

        // Merge and filter
        $transactions = $deposits->merge($transfers)->merge($loans);

        if ($typeFilter) {
            $transactions = $transactions->filter(fn($tx) => strtolower($tx['type']) === strtolower($typeFilter));
        }

        $transactions = $transactions->sortByDesc('created_at')->values();

        // Manual pagination
        $perPage = 15;
        $currentPage = $request->query('page', 1);
        $paginatedTransactions = new LengthAwarePaginator(
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

    // Download CSV (existing)
    public function download(Request $request)
    {
        // ... your existing CSV export logic ...
    }

    // View receipt in modal or separate page
    public function viewReceipt($type, $id)
    {
        $transaction = $this->getTransactionModel($type, $id);
        return view('account.user.transaction.receipt', compact('transaction'));
    }

    // Download PDF receipt
    public function downloadReceipt($type, $id)
    {
        $transaction = $this->getTransactionModel($type, $id);
        $pdf = Pdf::loadView('account.user.transaction.receipt', compact('transaction'));
        return $pdf->download('transaction_'.$transaction->id.'.pdf');
    }

    // Helper to get the correct model based on type
    private function getTransactionModel($type, $id)
    {
        switch(strtolower($type)) {
            case 'deposit':
                return Deposit::findOrFail($id);
            case 'transfer':
                return Transfer::findOrFail($id);
            case 'loan':
                return Loan::findOrFail($id);
            default:
                abort(404);
        }
    }
}
