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
        Schema::create('employee_proposal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('employee_proposals')->onDelete('cascade');
            $table->enum('item_type', ['labor', 'service']);
            $table->foreignId('labor_type_id')->nullable()->constrained('labor_types')->onDelete('set null');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_proposal_items');
    }
};
