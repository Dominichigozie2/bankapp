<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('crypto_types', function (Blueprint $table) {
            $table->string('network')->nullable()->after('slug'); // e.g., BEP20, ERC20, TRC20
        });
    }

    public function down(): void
    {
        Schema::table('crypto_types', function (Blueprint $table) {
            $table->dropColumn('network');
        });
    }
};
