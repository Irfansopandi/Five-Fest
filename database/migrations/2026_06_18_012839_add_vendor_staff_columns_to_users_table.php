<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','owner','vendor','vendor_staff','tenant','user') DEFAULT 'user'");

            // kolom untuk staf:menuju ke vendor induk
            $table->unsignedBigInteger('parent_vendor_id')->nullable()->after('role');
            $table->foreign('parent_vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_vendor_id']);
            $table->dropColumn('parent_vendor_id');
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','owner','vendor','tenant','user') DEFAULT 'user'");
        });
    }
};
