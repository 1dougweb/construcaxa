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
            // Verificar e adicionar latitude
            if (!Schema::hasColumn('inspections', 'latitude')) {
                try {
                    $table->decimal('latitude', 10, 8)->nullable()->after('address');
                } catch (\Exception $e) {
                    // Se falhar, tentar sem especificar posição
                    DB::statement('ALTER TABLE inspections ADD COLUMN latitude DECIMAL(10, 8) NULL AFTER address');
                }
            }
            
            // Verificar e adicionar longitude
            if (!Schema::hasColumn('inspections', 'longitude')) {
                try {
                    $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
                } catch (\Exception $e) {
                    // Se falhar, tentar sem especificar posição
                    DB::statement('ALTER TABLE inspections ADD COLUMN longitude DECIMAL(11, 8) NULL AFTER latitude');
                }
            }
            
            // Verificar e adicionar qr_code_path
            if (!Schema::hasColumn('inspections', 'qr_code_path')) {
                try {
                    $table->string('qr_code_path')->nullable()->after('pdf_path');
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE inspections ADD COLUMN qr_code_path VARCHAR(255) NULL AFTER pdf_path');
                }
            }
            
            // Verificar e adicionar public_token
            if (!Schema::hasColumn('inspections', 'public_token')) {
                try {
                    $table->string('public_token')->nullable()->unique()->after('qr_code_path');
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE inspections ADD COLUMN public_token VARCHAR(255) NULL AFTER qr_code_path');
                    // Adicionar índice único separadamente
                    try {
                        DB::statement('CREATE UNIQUE INDEX inspections_public_token_unique ON inspections(public_token)');
                    } catch (\Exception $e2) {
                        // Índice pode já existir
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (Schema::hasColumn('inspections', 'public_token')) {
                try {
                    DB::statement('DROP INDEX inspections_public_token_unique ON inspections');
                } catch (\Exception $e) {
                    // Ignorar se não existir
                }
                $table->dropColumn('public_token');
            }
            if (Schema::hasColumn('inspections', 'qr_code_path')) {
                $table->dropColumn('qr_code_path');
            }
            if (Schema::hasColumn('inspections', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('inspections', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};
