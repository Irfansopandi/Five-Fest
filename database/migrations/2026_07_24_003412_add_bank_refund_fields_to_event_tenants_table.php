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
            $table->string('refund_bank_name')->nullable()->after('refund_reason');
            $table->string('refund_account_number')->nullable()->after('refund_bank_name');
            $table->string('refund_account_name')->nullable()->after('refund_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tenants', function (Blueprint $table) {
            $table->dropColumn(['refund_bank_name', 'refund_account_number', 'refund_account_name']);
        });
    }
};
