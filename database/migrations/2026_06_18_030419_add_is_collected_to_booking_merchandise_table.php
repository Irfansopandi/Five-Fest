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
        Schema::table('booking_merchandise', function (Blueprint $table) {
            $table->boolean('is_collected')->default(false)->after('price');
            $table->timestamp('collected_at')->nullable()->after('is_collected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_merchandise', function (Blueprint $table) {
            $table->dropColumn(['is_collected', 'collected_at']);
        });
    }
};
