<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method')->default('bank_transfer')->after('status');
            $table->string('stripe_session_id')->nullable()->after('payment_method');
            $table->text('gateway_response')->nullable()->after('stripe_session_id');
            $table->string('currency', 10)->default('NGN')->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'stripe_session_id', 'gateway_response', 'currency']);
        });
    }
};
