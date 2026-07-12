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
        Schema::table('events', function (Blueprint $table) {
            $table->string('terms_image')->nullable()->after('terms');
            $table->string('venue_map')->nullable()->after('venue');
            $table->time('gate_open_time')->nullable()->after('time');
            $table->text('venue_location_url')->nullable()->after('venue_map');
            $table->integer('max_tickets_per_user')->default(4)->after('available_tickets');
            $table->timestamp('last_update_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'terms_image',
                'venue_map',
                'gate_open_time',
                'venue_location_url',
                'max_tickets_per_user',
                'last_update_at'
            ]);
        });
    }
};
