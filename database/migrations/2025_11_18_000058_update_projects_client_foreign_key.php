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
        $projects = DB::table('projects')
            ->whereNotNull('client_id')
            ->get();

        foreach ($projects as $project) {
            $client = DB::table('clients')
                ->where('user_id', $project->client_id)
                ->first();

            if ($client) {
                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['client_id' => $client->id]);
            }
        }

        // Remover foreign key antiga
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });

        // Adicionar nova foreign key para clients
        Schema::table('projects', function (Blueprint $table) {
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
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });

        // Reverter client_ids de clients para users
        $projects = DB::table('projects')
            ->whereNotNull('client_id')
            ->get();

        foreach ($projects as $project) {
            $client = DB::table('clients')
                ->where('id', $project->client_id)
                ->first();

            if ($client && $client->user_id) {
                DB::table('projects')
                    ->where('id', $project->id)
                    ->update(['client_id' => $client->user_id]);
            }
        }

        // Adicionar foreign key antiga para users
        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }
};
