<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->text('cot_dep_message')->nullable()->after('cot_message');
            $table->text('tax_dep_message')->nullable()->after('tax_message');
            $table->text('imf_dep_message')->nullable()->after('imf_message');
        });
    }

    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropColumn(['cot_dep_message', 'tax_dep_message', 'imf_dep_message']);
        });
    }
};
