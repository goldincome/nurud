<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('markup_rules', function (Blueprint $table) {
            $table->string('currency_code', 3)
                ->default(strtolower(config('currency.default_currency')))
                ->after('markup_value');
        });

        // Normalize any existing data to lowercase
        DB::table('markup_rules')->update([
            'currency_code' => DB::raw('LOWER(currency_code)')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('markup_rules', function (Blueprint $table) {
            $table->dropColumn('currency_code');
        });
    }
};
