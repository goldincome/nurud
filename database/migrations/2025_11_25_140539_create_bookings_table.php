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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('pnr_reference', 20)->unique();
            $table->string('flight_offer_id');
            $table->string('guest_email');
            $table->string('guest_phone');
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 3)->default('NGN');
            $table->enum('status', ['reserved', 'pending_payment', 'confirmed', 'cancelled', 'expired'])->default('reserved');
            $table->enum('payment_method', ['stripe', 'bank'])->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('ticket_issued_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('pnr_reference');
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
