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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->date('issue_date');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['cash', 'pix', 'credit_card', 'debit_card', 'bank_transfer', 'check', 'other'])->default('cash');
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('receipts');
    }
};
