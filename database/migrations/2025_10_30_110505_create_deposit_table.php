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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // make account_type_id nullable because cheque deposits may not have one
            $table->foreignId('account_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('crypto_type_id')->nullable()->constrained('crypto_types')->nullOnDelete();
            $table->decimal('amount', 15, 2)->nullable(); // mobile requires amount; cheque not necessarily
            $table->enum('method', ['cheque', 'mobile', 'crypto']);
            $table->string('proof_url')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit');
    }
};
