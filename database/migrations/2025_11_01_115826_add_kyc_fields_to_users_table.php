<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('idfront')->nullable();
            $table->string('idback')->nullable();
            $table->string('id_no')->nullable();
            $table->string('addressproof')->nullable();
            $table->enum('kyc_status', ['empty', 'pending', 'successful'])->default('empty');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['idfront', 'idback', 'id_no', 'addressproof', 'kyc_status']);
        });
    }
};
