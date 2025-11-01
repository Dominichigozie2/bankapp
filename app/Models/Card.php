<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Card extends Model
{
    protected $fillable = [
        'user_id','serial_key','internet_id','card_number','last4',
        'card_name','card_expiration','card_security','payment_account',
        'card_limit','card_limit_remain','card_status'
    ];

    // Optionally hide encrypted fields from array/JSON
    protected $hidden = ['card_number', 'card_security'];

    // Accessor - show masked number when needed
    protected function maskedNumber(): Attribute
    {
        return Attribute::get(function () {
            return '**** **** **** ' . ($this->last4 ?? '1234');
        });
    }
}
