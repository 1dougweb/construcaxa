<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Update project_budgets table to add new status values
        Schema::table('project_budgets', function (Blueprint $table) {
            // First, update existing status values to new ones
            DB::statement("UPDATE project_budgets SET status = 'pending' WHERE status = 'draft'");
            DB::statement("UPDATE project_budgets SET status = 'under_review' WHERE status = 'sent'");
            
            // Change the status column to support new values
            $table->string('status')->default('pending')->change();
        });

        // Add OS number to projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->string('os_number')->nullable()->unique()->after('code');
        });
    }

    public function down(): void
    {
        // Revert status changes
        Schema::table('project_budgets', function (Blueprint $table) {
            DB::statement("UPDATE project_budgets SET status = 'draft' WHERE status = 'pending'");
            DB::statement("UPDATE project_budgets SET status = 'sent' WHERE status = 'under_review'");
            
            $table->string('status')->default('draft')->change();
        });

        // Remove OS number from projects
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('os_number');
        });
    }
};
