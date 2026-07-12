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
        Schema::table('event_tenants', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('snap_token')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tenants', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'snap_token']);
        });
    }
};
