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
        Schema::create('inspection_environments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technical_inspection_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('technical_notes')->nullable();
            $table->json('photos')->nullable();
            $table->json('videos')->nullable();
            $table->text('measurements')->nullable();
            $table->string('google_drive_link')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('technical_inspection_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_environments');
    }
};
