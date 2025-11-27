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
        Schema::create('inspection_environment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_environment_id')->constrained('inspection_environments')->onDelete('cascade');
            $table->string('title');
            $table->enum('quality_rating', ['excellent', 'good', 'regular', 'poor'])->default('good');
            $table->text('observations')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('inspection_environment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_environment_items');
    }
};
