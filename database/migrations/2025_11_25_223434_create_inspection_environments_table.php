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
        if (!Schema::hasTable('inspection_environments')) {
            Schema::create('inspection_environments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inspection_id')->constrained('inspections')->onDelete('cascade');
                $table->foreignId('template_id')->nullable()->constrained('inspection_environment_templates')->nullOnDelete();
                $table->string('name');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                $table->index('inspection_id');
                $table->index('template_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_environments');
    }
};
