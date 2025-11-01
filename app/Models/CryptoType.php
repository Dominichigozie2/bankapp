<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'min_balance', 'wallet_address'];
}
