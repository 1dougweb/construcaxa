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
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('material_request_id')->constrained('projects')->nullOnDelete();
            $table->decimal('cost_price', 10, 2)->nullable()->after('quantity')->comment('Preço de custo do produto no momento da movimentação');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn(['project_id', 'cost_price']);
        });
    }
};
