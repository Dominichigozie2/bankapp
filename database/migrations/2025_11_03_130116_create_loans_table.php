<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 15, 2);
            $table->string('loan_type')->nullable(); // business, individual, student...
            $table->string('duration')->nullable(); // e.g. "1 week", "3 months"
            $table->string('bank_code')->nullable(); // account code / account identity
            $table->text('details')->nullable();
            $table->tinyInteger('status')->default(2); 
            // status: 0=rejected, 1=approved (active), 2=pending, 3=on_hold, 4=repaid
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id','status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
