<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','account_type_id','account_number','account_amount','is_active'];

    public function accountType()
    {
        return $this->belongsTo(\App\Models\AccountType::class, 'account_type_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
