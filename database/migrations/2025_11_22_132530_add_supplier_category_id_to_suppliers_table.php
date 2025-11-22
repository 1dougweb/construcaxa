<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Verificar se a coluna já existe
            if (!Schema::hasColumn('suppliers', 'supplier_category_id')) {
                $table->foreignId('supplier_category_id')->nullable()->after('contact_person')->constrained('supplier_categories')->onDelete('set null');
            } else {
                // Se a coluna já existe, apenas adicionar a foreign key se não existir
                try {
                    $table->foreign('supplier_category_id')
                        ->references('id')
                        ->on('supplier_categories')
                        ->onDelete('set null');
                } catch (\Exception $e) {
                    // Foreign key já existe, ignorar
                }
            }
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['supplier_category_id']);
            $table->dropColumn('supplier_category_id');
        });
    }
};
