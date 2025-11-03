<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanLimitsTable extends Migration
{
    public function up()
    {
        Schema::create('loan_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // null = global/default
            $table->decimal('limit_amount', 15, 2);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id']); // only one limit row per user (or null row)
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_limits');
    }
}
