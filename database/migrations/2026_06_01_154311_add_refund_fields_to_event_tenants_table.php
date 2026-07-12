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
            $table->text('refund_reason')->nullable()->after('midtrans_order_id');
            $table->enum('refund_status', [
                'none',
                'requested',
                'approved',
                'completed'
            ])->default('none')->after('refund_reason');
            $table->timestamp('refund_requested_at')->nullable()->after('refund_status');
            $table->timestamp('refund_approved_at')->nullable()->after('refund_requested_at');
            $table->timestamp('refund_completed_at')->nullable()->after('refund_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('event_tenants', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'refund_reason',
                'refund_status',
                'refund_requested_at',
                'refund_approved_at',
                'refund_completed_at'
            ]);
        });
    }
};
