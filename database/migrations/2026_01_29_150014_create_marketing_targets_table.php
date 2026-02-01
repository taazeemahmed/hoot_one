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
        Schema::create('marketing_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The marketing member
            $table->string('month_year'); // Format: 'Y-m' e.g., '2026-01'
            $table->integer('leads_target')->default(0); // Target number of leads to add
            $table->integer('leads_assigned_target')->default(0); // Target number to assign (quality check)
            $table->timestamps();

            $table->unique(['user_id', 'month_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_targets');
    }
};
