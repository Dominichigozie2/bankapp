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
    Schema::table('admin_settings', function (Blueprint $table) {
        $table->string('site_email')->nullable()->after('transfer_success_message');
        $table->string('site_logo')->nullable()->after('site_email');
    });
}

public function down()
{
    Schema::table('admin_settings', function (Blueprint $table) {
        $table->dropColumn(['site_email', 'site_logo']);
    });
}

};
