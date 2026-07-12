<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        // Relasi ke pembuat (Admin/Vendor)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
        $table->string('title');
        $table->string('artist')->nullable();
        $table->text('description');
        $table->text('terms')->nullable(); // Tambahkan kolom syarat & ketentuan
        $table->string('venue');
        $table->date('date');
        $table->time('time');
        $table->string('image')->nullable(); // Untuk Poster
        $table->string('seat_plan')->nullable(); // Untuk Denah Kursi
        $table->decimal('price', 15, 2)->default(0);
        $table->string('category');
        $table->integer('capacity')->default(0); // Total Kuota
        $table->integer('available_tickets')->default(0); // Sisa Tiket
        $table->enum('status', ['active', 'inactive', 'cancelled'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};