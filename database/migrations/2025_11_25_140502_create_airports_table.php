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
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->char('code', 3)->unique(); // IATA code, e.g., LOS
            $table->string('name');
            $table->string('city');
            $table->string('country');
            $table->timestamps();

            $table->text('search_vector')->storedAs('CONCAT(name, " ", city, " ", code)')->nullable(); // Generated column for fulltext search

            $table->fullText(['search_vector'], 'search_vector_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
