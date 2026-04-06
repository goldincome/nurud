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
        Schema::create('price_in_pounds', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->string('currency')->default('GBP');
            $table->decimal('price', 12, 2);
            $table->decimal('tax', 12, 2);
            $table->decimal('markup', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_in_pounds');
    }
};
