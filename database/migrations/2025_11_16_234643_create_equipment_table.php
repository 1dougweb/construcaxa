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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->enum('status', ['available', 'borrowed', 'maintenance', 'retired'])->default('available');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('current_employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
