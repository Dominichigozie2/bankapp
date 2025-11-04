<?php

// database/migrations/2025_11_04_000000_create_tickets_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // visible ID like TKT-0000123
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->nullable();      // "MyAccount", "Security", etc.
            $table->string('account_code')->nullable();
            $table->enum('status', ['open','pending','closed'])->default('open');
            $table->boolean('user_unread')->default(false);   // optional flags for UI
            $table->boolean('admin_unread')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
