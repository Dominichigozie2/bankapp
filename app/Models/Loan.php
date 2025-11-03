<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'repayment_amount',
        'loan_type',
        'duration',
        'bank_code',
        'details',
        'status',
        'approved_amount',
        'approved_by',
        'approved_at',
        'due_date', // ✅ added this
    ];

    protected $dates = [
        'approved_at',
        'due_date', // ✅ ensures it's treated as Carbon instance
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // status helpers
    public function isPending() { return $this->status == 2; }
    public function isApproved() { return $this->status == 1; }
    public function isOnHold() { return $this->status == 3; }
    public function isRejected() { return $this->status == 0; }
}
