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
        if (!Schema::hasTable('inspections')) {
            Schema::create('inspections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
                $table->string('number')->unique();
                $table->unsignedInteger('version')->default(1);
                $table->date('inspection_date');
                $table->text('address')->nullable();
                $table->text('description')->nullable();
                $table->foreignId('inspector_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
                $table->json('photos')->nullable();
                $table->string('pdf_path')->nullable();
                $table->string('signed_document_path')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('budget_id')->nullable()->constrained('project_budgets')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['client_id', 'status']);
                $table->index('inspection_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
