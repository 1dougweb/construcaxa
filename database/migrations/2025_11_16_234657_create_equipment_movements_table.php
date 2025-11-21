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
        Schema::create('equipment_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('restrict');
            $table->foreignId('employee_id')->constrained()->onDelete('restrict');
            $table->foreignId('equipment_request_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['loan', 'return', 'maintenance', 'repair'])->default('loan');
            $table->text('notes')->nullable();
            $table->text('condition_before')->nullable();
            $table->text('condition_after')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_movements');
    }
};
