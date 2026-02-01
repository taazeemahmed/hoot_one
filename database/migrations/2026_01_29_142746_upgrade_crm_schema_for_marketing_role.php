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
        // 1. Update Users Table Role Enum
        // Raw statement for MySQL compatibility
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'representative', 'marketing_member') NOT NULL DEFAULT 'representative'");

        // 2. Update Patients Table
        Schema::table('patients', function (Blueprint $table) {
            // Make representative_id nullable
            $table->unsignedBigInteger('representative_id')->nullable()->change();

            // Add new columns
            $table->enum('lead_status', ['new', 'assigned', 'follow_up', 'converted', 'not_interested'])->default('new')->after('representative_id');
            $table->enum('lead_quality', ['hot', 'warm', 'cold', 'invalid'])->default('cold')->after('lead_status');
            $table->enum('source', ['whatsapp', 'call', 'website', 'referral'])->default('call')->after('lead_quality');
            
            $table->foreignId('assigned_by')->nullable()->after('source')->constrained('users');
            $table->timestamp('assigned_at')->nullable()->after('assigned_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->dropColumn(['lead_status', 'lead_quality', 'source', 'assigned_by', 'assigned_at']);
            // Cannot easily revert nullable change without potential data issues, but structurally we can try
            // $table->unsignedBigInteger('representative_id')->nullable(false)->change(); 
        });

        // Revert Role Enum (Warning: verify no marketing_members exist before running this in real prod)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'representative') NOT NULL DEFAULT 'representative'");
    }
};
