<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            }
            if (!Schema::hasColumn('users', 'document_type')) {
                $table->string('document_type')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'npwp_number')) {
                $table->string('npwp_number')->nullable()->after('document_type');
            }
            if (!Schema::hasColumn('users', 'npwp_name')) {
                $table->string('npwp_name')->nullable()->after('npwp_number');
            }
            if (!Schema::hasColumn('users', 'npwp_address')) {
                $table->text('npwp_address')->nullable()->after('npwp_name');
            }
            if (!Schema::hasColumn('users', 'npwp_file')) {
                $table->string('npwp_file')->nullable()->after('npwp_address');
            }
            if (!Schema::hasColumn('users', 'nib_number')) {
                $table->string('nib_number')->nullable()->after('npwp_file');
            }
            if (!Schema::hasColumn('users', 'anggaran_dasar_file')) {
                $table->string('anggaran_dasar_file')->nullable()->after('nib_number');
            }
            if (!Schema::hasColumn('users', 'verification_status')) {
                $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('anggaran_dasar_file');
            }
            if (!Schema::hasColumn('users', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('verification_status');
            }
            if (!Schema::hasColumn('users', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('rejection_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'document_type', 'npwp_number', 'npwp_name', 'npwp_address', 
                'npwp_file', 'nib_number', 'anggaran_dasar_file', 
                'verification_status', 'rejection_reason', 'verified_at'
            ]);
        });
    }
};
