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
        Schema::create('technical_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->date('inspection_date');
            $table->text('address');
            $table->decimal('unit_area', 10, 2)->nullable();
            $table->string('furniture_status')->nullable();
            $table->string('map_image_path')->nullable();
            $table->json('coordinates')->nullable();
            $table->string('responsible_name');
            $table->text('involved_parties')->nullable();
            $table->integer('total_photos_count')->default(0);
            $table->enum('status', ['draft', 'in_progress', 'completed', 'archived'])->default('draft');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index('inspection_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_inspections');
    }
};
