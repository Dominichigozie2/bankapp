<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $table = 'admin_settings';

    protected $fillable = [
        'cot_enabled',
        'tax_enabled',
        'imf_enabled',
        'transfers_enabled',
        'service_charge',
        'max_transfer_amount',
        'transfer_success_message',

        // ✅ Existing fields
        'global_cot_code',
        'global_tax_code',
        'global_imf_code',
        'cot_message',
        'tax_message',
        'imf_message',
        'transfer_instruction',
        'deposit_instruction',

        // ✅ Newly added deposit message fields
        'cot_dep_message',
        'tax_dep_message',
        'imf_dep_message',
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
