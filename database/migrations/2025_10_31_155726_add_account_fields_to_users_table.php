<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('passcode_allow')->default(true)->after('passcode');
        $table->decimal('current_account_bal', 15, 2)->default(0)->after('passcode_allow');
        $table->decimal('savings_account_bal', 15, 2)->default(0)->after('current_account_bal');
        $table->string('current_account_number')->unique()->nullable()->after('savings_account_bal');
        $table->string('savings_account_number')->unique()->nullable()->after('current_account_number');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'passcode_allow',
            'current_account_bal',
            'savings_account_bal',
            'current_account_number',
            'savings_account_number'
        ]);
    });
}

};
