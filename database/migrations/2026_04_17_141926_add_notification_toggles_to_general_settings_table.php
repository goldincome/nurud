<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->boolean('notify_admin_new_reservation')->default(true)->after('admin_email');
            $table->boolean('notify_admin_stripe_no_ticket')->default(true)->after('notify_admin_new_reservation');
            $table->boolean('notify_admin_247_api_down')->default(true)->after('notify_admin_stripe_no_ticket');
            $table->boolean('notify_admin_simlesspay_api_down')->default(true)->after('notify_admin_247_api_down');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'notify_admin_new_reservation',
                'notify_admin_stripe_no_ticket',
                'notify_admin_247_api_down',
                'notify_admin_simlesspay_api_down',
            ]);
        });
    }
};
