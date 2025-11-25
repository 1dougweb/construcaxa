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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // equipment_loan, material_request, budget_approval, proposal_approval
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Dados adicionais (IDs, links, etc)
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Índices para performance
            $table->index('user_id');
            $table->index('read_at');
            $table->index('type');
            $table->index(['user_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
