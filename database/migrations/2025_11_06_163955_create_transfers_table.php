<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['local', 'international']);
            $table->decimal('amount', 16, 2);
            $table->string('bank_name');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_country')->nullable();
            $table->string('routine_number')->nullable();
            $table->string('bank_code')->nullable();
            $table->text('details')->nullable();
            $table->string('reference')->unique();
            $table->enum('status', ['pending','success','failed'])->default('pending');
            $table->json('meta')->nullable(); // store codes entered or extra meta (optional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
