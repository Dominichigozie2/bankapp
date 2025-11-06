<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCode extends Model
{
    protected $fillable = [
        'user_id', 'cot_code', 'tax_code', 'imf_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
