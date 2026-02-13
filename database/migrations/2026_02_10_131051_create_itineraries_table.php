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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->string('itinerary_title')->nullable();
            $table->string('itinerary_summary')->nullable();
            $table->integer('itinerary_index')->nullable();
            $table->string('duration')->nullable();
            $table->integer('duration_in_minutes')->nullable();
            $table->json('segments')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
