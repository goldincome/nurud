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
        Schema::table('users', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('type')->default('customer');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone_code')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'first_name',
                'middle_name',
                'last_name',
                'type',
                'gender',
                'phone_code',
                'phone_no',
                'country',
                'postal_code',
                'city',
                'address'
            ]);
        });
    }
};
