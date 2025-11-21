<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Project;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('code');
            $table->index('slug');
        });

        // Gerar slugs para projetos existentes
        Project::chunk(100, function ($projects) {
            foreach ($projects as $project) {
                $project->slug = Str::slug($project->name . '-' . $project->code);
                $project->save();
            }
        });

        // Tornar slug obrigatório após gerar para registros existentes
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
