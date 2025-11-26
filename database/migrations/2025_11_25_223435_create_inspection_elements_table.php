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
        Schema::create('inspection_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_environment_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('technical_notes')->nullable();
            $table->enum('condition_status', ['poor', 'fair', 'good', 'very_good', 'excellent'])->default('good');
            $table->json('photos')->nullable();
            $table->text('measurements')->nullable();
            $table->text('defects_identified')->nullable();
            $table->text('probable_causes')->nullable();
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
        Schema::dropIfExists('inspection_elements');
    }
};
