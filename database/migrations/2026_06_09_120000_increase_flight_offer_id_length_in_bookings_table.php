<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function () {
            DB::statement('ALTER TABLE bookings MODIFY flight_offer_id TEXT NULL');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function () {
            DB::statement('ALTER TABLE bookings MODIFY flight_offer_id VARCHAR(255) NULL');
        });
    }
};
