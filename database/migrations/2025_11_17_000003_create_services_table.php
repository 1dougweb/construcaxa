<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->enum('unit_type', ['hour', 'fixed', 'per_unit'])->default('hour');
            $table->decimal('default_price', 10, 2);
            $table->decimal('minimum_price', 10, 2)->nullable();
            $table->decimal('maximum_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_active', 'category_id']);
            $table->index(['name', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
