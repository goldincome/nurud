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
        Schema::create('travelers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->unsignedBigInteger('base_price')->default(0);
            $table->unsignedBigInteger('taxes_and_fees')->default(0);
            $table->unsignedBigInteger('total_price')->default(0);
            $table->integer('gender')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country_calling_code')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->string('traveler_id')->nullable();
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_modified')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('booking_id');
            $table->index('email');
            $table->index('traveler_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travelers');
    }
};
