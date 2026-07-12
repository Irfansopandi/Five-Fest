<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE event_tenants MODIFY COLUMN refund_status ENUM('none', 'requested', 'approved', 'rejected', 'completed') DEFAULT 'none'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE event_tenants MODIFY COLUMN refund_status ENUM('none', 'requested', 'approved', 'completed') DEFAULT 'none'");
    }
};