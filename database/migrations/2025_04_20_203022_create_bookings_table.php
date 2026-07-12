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
    $table->foreignId('user_id')->constrained();
    $table->foreignId('event_id')->constrained();
    $table->foreignId('ticket_category_id')->constrained(); // WAJIB ADA INI
    $table->string('booking_code')->unique();
    $table->integer('quantity');
    $table->decimal('total_price', 15, 2);
    $table->string('payment_method')->nullable();
    $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
    $table->enum('booking_status', ['pending', 'confirmed', 'cancelled'])->default('pending');
    $table->timestamps();
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
