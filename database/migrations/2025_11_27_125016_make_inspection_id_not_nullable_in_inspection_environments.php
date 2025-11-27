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
        // Primeiro, garantir que não há registros com inspection_id NULL que precisam ser limpos
        // (opcional - apenas se necessário)
        
        // Tornar a coluna NOT NULL apenas se não houver registros NULL
        $nullCount = DB::table('inspection_environments')->whereNull('inspection_id')->count();
        
        if ($nullCount === 0) {
            Schema::table('inspection_environments', function (Blueprint $table) {
                // Modificar a coluna para NOT NULL
                DB::statement('ALTER TABLE inspection_environments MODIFY COLUMN inspection_id BIGINT UNSIGNED NOT NULL');
            });
        }
        
        // Garantir que a foreign key existe e está correta
        try {
            // Remover constraint antiga se existir com nome diferente
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'inspection_environments' 
                AND COLUMN_NAME = 'inspection_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            // Adicionar constraint se não existir
            if (empty($constraints)) {
                DB::statement('ALTER TABLE inspection_environments ADD CONSTRAINT inspection_environments_inspection_id_foreign FOREIGN KEY (inspection_id) REFERENCES inspections(id) ON DELETE CASCADE');
            }
        } catch (\Exception $e) {
            // Constraint pode já existir
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_environments', function (Blueprint $table) {
            // Tornar nullable novamente
            DB::statement('ALTER TABLE inspection_environments MODIFY COLUMN inspection_id BIGINT UNSIGNED NULL');
        });
    }
};
