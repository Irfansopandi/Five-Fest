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
        Schema::table('merchandises', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('price');
            $table->integer('sort_order')->default(0)->after('stock');
        });

        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchandises', function (Blueprint $table) {
            $table->dropColumn(['stock', 'sort_order']);
        });

        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
