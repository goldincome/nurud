<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Programmatic SEO flight-route landing pages.
     *
     * Airport details are intentionally NOT referenced from the `airports`
     * table. Airport city/name/country data is always resolved from
     * database/data/airports.json (via App\Services\AirportService) so the
     * autocomplete feed and the SEO pages share one source of truth.
     */
    public function up(): void
    {
        Schema::create('flight_routes', function (Blueprint $table) {
            $table->id();
            $table->char('origin_code', 3)->index();
            $table->char('destination_code', 3)->index();
            $table->string('slug')->unique()->index(); // e.g. london-lhr-to-lagos-los
            $table->unsignedInteger('distance_miles')->nullable();
            $table->unsignedInteger('avg_duration_minutes')->nullable();
            $table->boolean('has_direct_flights')->default(true);
            $table->decimal('lowest_fare_gbp', 8, 2)->index();
            $table->string('primary_airline')->nullable();
            $table->boolean('is_indexable')->default(true)->index();
            $table->json('search_intent_metadata')->nullable(); // faqs[], airlines_operating[], seasonal_tips[]
            $table->timestamps();

            $table->unique(['origin_code', 'destination_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_routes');
    }
};