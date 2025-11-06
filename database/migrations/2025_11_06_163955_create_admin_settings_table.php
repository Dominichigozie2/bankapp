<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('cot_enabled')->default(false);
            $table->boolean('tax_enabled')->default(false);
            $table->boolean('imf_enabled')->default(false);
            $table->boolean('transfers_enabled')->default(true); // global toggle for transfers
            $table->decimal('service_charge', 8, 2)->default(0.00); // percent or flat — we’ll interpret as flat currency for now
            $table->decimal('max_transfer_amount', 16, 2)->default(10000.00); // admin-editable limit
            $table->string('transfer_success_message')->default('Your transfer was successful!');
            $table->timestamps();
        });

        // Seed a default row so there's always one settings row
        DB::table('admin_settings')->insert([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_settings');
    }
};
