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
        Schema::create('markup_rules', function (Blueprint $table) {
            $table->id();
            $table->string('operator'); // >=, <=, >, <, ==
            $table->decimal('threshold_price', 15, 2);
            $table->enum('markup_type', ['percentage', 'flat']);
            $table->decimal('markup_value', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markup_rules');
    }
};
