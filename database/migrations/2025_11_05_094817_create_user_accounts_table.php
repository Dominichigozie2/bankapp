<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_type_id'); // from account_types table
            $table->string('account_number')->nullable(); // optional bank account number
            $table->float('account_amount')->nullable(); // optional bank account number
            $table->boolean('is_active')->default(false); // which account user currently uses/display
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
