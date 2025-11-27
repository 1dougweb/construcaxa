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
            // Verificar se a coluna inspection_id não existe
            if (!Schema::hasColumn('inspection_environments', 'inspection_id')) {
                // Se existe technical_inspection_id, podemos renomear ou adicionar nova coluna
                if (Schema::hasColumn('inspection_environments', 'technical_inspection_id')) {
                    // Adicionar nova coluna
                    $table->unsignedBigInteger('inspection_id')->nullable()->after('id');
                    
                    // Copiar dados se necessário (opcional)
                    // DB::statement('UPDATE inspection_environments SET inspection_id = technical_inspection_id WHERE technical_inspection_id IS NOT NULL');
                } else {
                    // Adicionar coluna normalmente
                    $table->foreignId('inspection_id')->nullable()->after('id')->constrained('inspections')->onDelete('cascade');
                }
            }
            
            // Verificar se template_id não existe
            if (!Schema::hasColumn('inspection_environments', 'template_id')) {
                $table->foreignId('template_id')->nullable()->after('inspection_id')->constrained('inspection_environment_templates')->nullOnDelete();
            }
            
            // Garantir que sort_order existe
            if (!Schema::hasColumn('inspection_environments', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('name');
            }
        });
        
        // Adicionar foreign key se não existir
        try {
            DB::statement('ALTER TABLE inspection_environments ADD CONSTRAINT inspection_environments_inspection_id_foreign FOREIGN KEY (inspection_id) REFERENCES inspections(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Constraint pode já existir
        }
        
        // Adicionar índice se não existir
        try {
            DB::statement('CREATE INDEX inspection_environments_inspection_id_index ON inspection_environments(inspection_id)');
        } catch (\Exception $e) {
            // Índice pode já existir
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_environments', function (Blueprint $table) {
            if (Schema::hasColumn('inspection_environments', 'inspection_id')) {
                try {
                    DB::statement('ALTER TABLE inspection_environments DROP FOREIGN KEY inspection_environments_inspection_id_foreign');
                } catch (\Exception $e) {
                    // Ignorar
                }
                $table->dropColumn('inspection_id');
            }
        });
    }
};
