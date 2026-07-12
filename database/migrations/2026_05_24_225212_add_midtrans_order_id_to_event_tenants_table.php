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
            $table->string('midtrans_order_id')->nullable()->after('snap_token');
            $table->text('refund_reason')->nullable()->after('midtrans_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tenants', function (Blueprint $table) {
            $table->dropColumn('midtrans_order_id');
            $table->dropColumn('refund_reason');
        });
    }
};
