<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->date('period_start')->nullable()->after('file_name');
            $table->date('period_end')->nullable()->after('period_start');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['day', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['period_start', 'period_end']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->tinyInteger('day')->nullable();
            $table->tinyInteger('month')->nullable();
            $table->year('year')->nullable();
        });
    }
};