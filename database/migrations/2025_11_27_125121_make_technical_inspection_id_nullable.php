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
        Schema::table('inspection_environments', function (Blueprint $table) {
            // Tornar technical_inspection_id nullable
            if (Schema::hasColumn('inspection_environments', 'technical_inspection_id')) {
                DB::statement('ALTER TABLE inspection_environments MODIFY COLUMN technical_inspection_id BIGINT UNSIGNED NULL');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_environments', function (Blueprint $table) {
            // Tornar NOT NULL novamente (pode falhar se houver NULLs)
            if (Schema::hasColumn('inspection_environments', 'technical_inspection_id')) {
                try {
                    DB::statement('ALTER TABLE inspection_environments MODIFY COLUMN technical_inspection_id BIGINT UNSIGNED NOT NULL');
                } catch (\Exception $e) {
                    // Pode falhar se houver valores NULL
                }
            }
        });
    }
};
