<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();                     // primary key (id)
            $table->string('name')->unique(); // e.g. "Savings", "Current"
            $table->string('slug')->nullable()->unique(); // machine-friendly name, optional
            $table->text('description')->nullable();
            $table->decimal('min_balance', 16, 2)->default(0); // optional business rule
            $table->timestamps();             // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
