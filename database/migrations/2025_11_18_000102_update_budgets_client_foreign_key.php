<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro, migrar os client_ids de users para clients
        $budgets = DB::table('project_budgets')
            ->whereNotNull('client_id')
            ->get();

        foreach ($budgets as $budget) {
            $client = DB::table('clients')
                ->where('user_id', $budget->client_id)
                ->first();

            if ($client) {
                DB::table('project_budgets')
                    ->where('id', $budget->id)
                    ->update(['client_id' => $client->id]);
            }
        }

        // Remover foreign key antiga
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });

        // Adicionar nova foreign key para clients
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover foreign key para clients
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });

        // Reverter client_ids de clients para users
        $budgets = DB::table('project_budgets')
            ->whereNotNull('client_id')
            ->get();

        foreach ($budgets as $budget) {
            $client = DB::table('clients')
                ->where('id', $budget->client_id)
                ->first();

            if ($client && $client->user_id) {
                DB::table('project_budgets')
                    ->where('id', $budget->id)
                    ->update(['client_id' => $client->user_id]);
            }
        }

        // Adicionar foreign key antiga para users
        Schema::table('project_budgets', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }
};
