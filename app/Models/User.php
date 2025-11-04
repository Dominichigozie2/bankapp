<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'account_type_id',
        'currency_id',
        'password',
        'balance',
        'role',
        'current_account_number',
        'savings_account_number',
        'passcode',
        'passcode_allow',
        'id_no',
        'idfront',
        'idback',
        'addressproof',
        'kyc_status',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function tickets()
    {
        return $this->hasMany(\App\Models\Ticket::class, 'user_id');
    }

    public function ticketMessages()
    {
        return $this->hasMany(\App\Models\TicketMessage::class, 'user_id');
    }
}
