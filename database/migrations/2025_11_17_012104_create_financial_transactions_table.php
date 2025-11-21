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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type'); // 'account_payable', 'account_receivable', 'invoice', 'receipt'
            $table->unsignedBigInteger('transaction_id');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->timestamps();
            $table->index(['transaction_type', 'transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
