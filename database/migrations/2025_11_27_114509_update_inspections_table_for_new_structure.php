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
        Schema::table('inspections', function (Blueprint $table) {
            if (!Schema::hasColumn('inspections', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('inspector_id')->constrained('users')->nullOnDelete();
            }
        });
        
        // Atualizar status enum se necessário
        if (Schema::hasTable('inspections')) {
            DB::statement("ALTER TABLE inspections MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected', 'in_progress', 'completed', 'archived') DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (Schema::hasColumn('inspections', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
