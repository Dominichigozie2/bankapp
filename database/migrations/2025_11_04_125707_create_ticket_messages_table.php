<?php

// database/migrations/2025_11_04_000001_create_ticket_messages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // who sent it (null for system)
            $table->text('message');
            $table->enum('sender_type',['user','admin','system'])->default('user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_messages');
    }
}
