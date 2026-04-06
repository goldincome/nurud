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
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('office_id')->nullable();
            $table->uuid('ota_id')->nullable();
            $table->string('flight_offer_id')->nullable();
            $table->string('origin_location')->nullable();
            $table->string('origin_destination')->nullable();
            $table->string('carrier_code')->nullable();
            $table->integer('route_model')->nullable();
            $table->dateTime('departure_date')->nullable();
            $table->string('cabin')->nullable();
            $table->string('class')->nullable();
            $table->string('ama_client_ref')->nullable();
            $table->string('reservation_id')->nullable();
            $table->unsignedBigInteger('base_price')->default(0);
            $table->unsignedBigInteger('taxes_and_fees')->default(0);
            $table->unsignedBigInteger('total_price')->default(0);
            $table->unsignedBigInteger('markup_fee')->default(0);
            $table->string('contact_phone')->nullable();
            $table->string('customer_first_name')->nullable();
            $table->string('customer_last_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->dateTime('reservation_date')->nullable();
            $table->integer('order_status')->default(5);
            $table->dateTime('date_created')->nullable();
            $table->dateTime('date_modified')->nullable();

            // Additional fields from original migration
            $table->string('currency', 3)->default('NGN');
            $table->enum('payment_method', ['stripe', 'bank'])->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('ticket_issued_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('reservation_id');
            $table->index('order_status');
            $table->index('customer_email');
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
