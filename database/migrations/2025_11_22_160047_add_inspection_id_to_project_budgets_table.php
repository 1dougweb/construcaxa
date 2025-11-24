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
            if (!Schema::hasColumn('project_budgets', 'inspection_id')) {
                $table->foreignId('inspection_id')->nullable()->after('client_id')->constrained('inspections')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->dropForeign(['inspection_id']);
            $table->dropColumn('inspection_id');
        });
    }
};
