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
        Schema::create('product_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('project_budget_id')->constrained('project_budgets')->cascadeOnDelete();
            $table->decimal('quantity_reserved', 10, 2)->default(0)->comment('Quantidade reservada do produto para este orçamento');
            $table->timestamps();
            
            // Evitar duplicatas: um produto só pode ter uma reserva por orçamento
            $table->unique(['product_id', 'project_budget_id']);
            
            // Índices para consultas rápidas
            $table->index('product_id');
            $table->index('project_budget_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reservations');
    }
};
