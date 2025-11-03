<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanLimit extends Model
{
    protected $fillable = ['user_id','limit_amount'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // fetch effective limit: prefer user-specific else global
    public static function effectiveLimitFor($userId)
    {
        $userLimit = self::where('user_id', $userId)->first();
        if ($userLimit) return (float)$userLimit->limit_amount;

        $global = self::whereNull('user_id')->first();
        return $global ? (float)$global->limit_amount : null;
    }
}
