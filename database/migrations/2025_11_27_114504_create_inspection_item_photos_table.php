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
        Schema::create('inspection_item_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_environment_item_id')->constrained('inspection_environment_items')->onDelete('cascade');
            $table->string('photo_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('inspection_environment_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_item_photos');
    }
};
