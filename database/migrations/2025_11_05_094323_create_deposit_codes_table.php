<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deposit_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('generated_by')->nullable(); // admin user id who created it
            $table->unsignedBigInteger('user_id')->nullable(); // if null, it's general and can be assigned on use
            $table->string('code')->unique();
            $table->enum('status', ['active','used','revoked'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_codes');
    }
};
