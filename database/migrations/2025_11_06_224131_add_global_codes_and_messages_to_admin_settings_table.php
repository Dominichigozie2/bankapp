<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            // ✅ Only add missing columns — no duplicates
            if (!Schema::hasColumn('admin_settings', 'cot_message')) {
                $table->text('cot_message')->nullable();
            }

            if (!Schema::hasColumn('admin_settings', 'tax_message')) {
                $table->text('tax_message')->nullable();
            }

            if (!Schema::hasColumn('admin_settings', 'imf_message')) {
                $table->text('imf_message')->nullable();
            }

            if (!Schema::hasColumn('admin_settings', 'transfer_instruction')) {
                $table->text('transfer_instruction')->nullable();
            }

            if (!Schema::hasColumn('admin_settings', 'deposit_instruction')) {
                $table->text('deposit_instruction')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropColumn([
                'cot_message',
                'tax_message',
                'imf_message',
                'transfer_instruction',
                'deposit_instruction',
            ]);
        });
    }
};
