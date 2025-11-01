<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // keys and ids
            $table->string('serial_key')->unique();    // serial for the physical card
            $table->string('internet_id')->unique();   // virtual id for online usage

            // sensitive fields - encrypted in app
            $table->string('card_number');             // encrypted or masked in DB
            $table->string('last4', 4)->nullable();   // last 4 digits for display
            $table->string('card_name');               // name ON CARD
            $table->string('card_expiration', 5);      // MM/YY

            $table->string('card_security');           // encrypted CVV (don't store plaintext ideally)
            $table->string('payment_account');         // linked account/wallet id

            // limits & status
            $table->decimal('card_limit', 12, 2)->default(0);
            $table->decimal('card_limit_remain', 12, 2)->default(0);
            $table->tinyInteger('card_status')->default(2)
                  ->comment('1=Active,2=Processing,3=Hold,4=Paused');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
