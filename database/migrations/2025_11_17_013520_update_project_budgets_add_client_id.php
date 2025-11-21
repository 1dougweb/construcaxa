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
        Schema::table('project_budgets', function (Blueprint $table) {
            // Add client_id column
            $table->foreignId('client_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            
            // Make project_id nullable (projects will be created from approved budgets)
            $table->foreignId('project_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_budgets', function (Blueprint $table) {
            // Remove client_id
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
            
            // Make project_id required again
            $table->foreignId('project_id')->nullable(false)->change();
        });
    }
};
