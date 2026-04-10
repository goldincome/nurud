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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id')->constrained()->onDelete('cascade');
            $table->string('transaction_ref');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'completed', 'refunded', 'canceled', 'failed', 'paid'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index('booking_id');
            $table->index('status');
            $table->unique('transaction_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
