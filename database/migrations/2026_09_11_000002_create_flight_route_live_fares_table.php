<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Real SkyLink search results persisted by `flight-routes:update-prices`.
     *
     * The scheduled command runs a round-trip SkyLink search per indexable
     * flight route and stores the cheapest fetched offers here so the SEO
     * pages can render live fares without hitting the airline API on every
     * page view. Rows are replaced wholesale on each scheduled run.
     */
    public function up(): void
    {
        Schema::create('flight_route_live_fares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_route_id')->index();
            $table->string('airline')->nullable();
            $table->string('airline_code')->nullable();
            $table->decimal('price', 10, 2)->unsigned();
            $table->string('currency', 3)->default('GBP');
            $table->unsignedTinyInteger('stops')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->char('departure_code', 3)->nullable();
            $table->string('departure_time')->nullable();
            $table->date('departure_date')->nullable();
            $table->char('arrival_code', 3)->nullable();
            $table->string('arrival_time')->nullable();
            $table->date('arrival_date')->nullable();
            $table->json('all_offer')->nullable();
            $table->json('payload')->nullable();
            $table->date('searched_for_departure_date')->nullable();
            $table->date('searched_for_return_date')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('flight_route_id')
                ->references('id')
                ->on('flight_routes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_route_live_fares');
    }
};