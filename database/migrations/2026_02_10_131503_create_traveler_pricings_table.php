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
        Schema::create('traveler_pricings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->string('traveler_id')->nullable();
            $table->string('fare_option')->nullable();
            $table->string('traveler_type')->nullable();
            $table->json('price')->nullable();
            $table->json('price_breakdown')->nullable();
            $table->json('fare_details_by_segment')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('booking_id');
            $table->index('traveler_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traveler_pricings');
    }
};
