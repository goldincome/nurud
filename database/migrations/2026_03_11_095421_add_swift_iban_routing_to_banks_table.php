<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->string('swift_code')->nullable()->after('account_number');
            $table->string('iban')->nullable()->after('swift_code');
            $table->string('routing_number')->nullable()->after('iban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['swift_code', 'iban', 'routing_number']);
        });
    }
};
