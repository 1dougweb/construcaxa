<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_budget_items', function (Blueprint $table) {
            // Add item type to distinguish between products, services, and labor
            $table->enum('item_type', ['product', 'service', 'labor'])->default('product')->after('budget_id');
            
            // Add references to services and labor types
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete()->after('product_id');
            $table->foreignId('labor_type_id')->nullable()->constrained('labor_types')->nullOnDelete()->after('service_id');
            
            // Add hours field for labor calculations
            $table->decimal('hours', 8, 2)->nullable()->after('quantity');
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('hours');
            
            // Make product_id nullable since we now have services and labor
            $table->foreignId('product_id')->nullable()->change();
            
            // Add indexes for better performance
            $table->index(['item_type', 'budget_id']);
            $table->index(['service_id', 'item_type']);
            $table->index(['labor_type_id', 'item_type']);
        });
    }

    public function down(): void
    {
        Schema::table('project_budget_items', function (Blueprint $table) {
            $table->dropIndex(['item_type', 'budget_id']);
            $table->dropIndex(['service_id', 'item_type']);
            $table->dropIndex(['labor_type_id', 'item_type']);
            
            $table->dropForeign(['service_id']);
            $table->dropForeign(['labor_type_id']);
            
            $table->dropColumn(['item_type', 'service_id', 'labor_type_id', 'hours', 'overtime_hours']);
            
            // Restore product_id as required
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
