<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $table = 'admin_settings';

    protected $fillable = [
        'cot_enabled', 'tax_enabled', 'imf_enabled', 'transfers_enabled',
        'service_charge', 'max_transfer_amount', 'transfer_success_message'
    ];

    protected $casts = [
        'cot_enabled' => 'boolean',
        'tax_enabled' => 'boolean',
        'imf_enabled' => 'boolean',
        'transfers_enabled' => 'boolean',
        'service_charge' => 'decimal:2',
        'max_transfer_amount' => 'decimal:2',
    ];
}
