<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $hasAssignedBy = DB::selectOne(
            "SELECT 1 AS found FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'patients' AND COLUMN_NAME = 'assigned_by' LIMIT 1"
        );

        if (!$hasAssignedBy) {
            return;
        }

        $existingConstraint = DB::selectOne(
            "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'patients' AND COLUMN_NAME = 'assigned_by' AND REFERENCED_TABLE_NAME = 'users' LIMIT 1"
        );

        if ($existingConstraint && isset($existingConstraint->CONSTRAINT_NAME)) {
            DB::statement("ALTER TABLE `patients` DROP FOREIGN KEY `{$existingConstraint->CONSTRAINT_NAME}`");
        }

        DB::statement("ALTER TABLE `patients` MODIFY `assigned_by` BIGINT UNSIGNED NULL");

        $constraintName = 'patients_assigned_by_foreign';
        $alreadyExists = DB::selectOne(
            "SELECT 1 AS found FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'patients' AND CONSTRAINT_NAME = '{$constraintName}' LIMIT 1"
        );

        if (!$alreadyExists) {
            DB::statement(
                "ALTER TABLE `patients` ADD CONSTRAINT `{$constraintName}` FOREIGN KEY (`assigned_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE"
            );
        }
    }

    public function down(): void
    {
        $hasAssignedBy = DB::selectOne(
            "SELECT 1 AS found FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'patients' AND COLUMN_NAME = 'assigned_by' LIMIT 1"
        );

        if (!$hasAssignedBy) {
            return;
        }

        $constraintName = 'patients_assigned_by_foreign';
        $existing = DB::selectOne(
            "SELECT 1 AS found FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'patients' AND CONSTRAINT_NAME = '{$constraintName}' LIMIT 1"
        );

        if ($existing) {
            DB::statement("ALTER TABLE `patients` DROP FOREIGN KEY `{$constraintName}`");

            DB::statement(
                "ALTER TABLE `patients` ADD CONSTRAINT `{$constraintName}` FOREIGN KEY (`assigned_by`) REFERENCES `users`(`id`)"
            );
        }
    }
};
