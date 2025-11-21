<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Adiciona somente colunas que ainda não existem
        if (!Schema::hasColumn('projects', 'status')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('status')->default('planned')->after('notes');
                $table->index('status');
            });
        }

        if (!Schema::hasColumn('projects', 'budget_total')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->decimal('budget_total', 12, 2)->nullable()->after('status');
            });
        }

        if (!Schema::hasColumn('projects', 'budget_version')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->unsignedInteger('budget_version')->default(0)->after('budget_total');
            });
        }

        if (!Schema::hasColumn('projects', 'approved_at')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('budget_version');
            });
        }

        if (!Schema::hasColumn('projects', 'approved_by')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn(['status','budget_total','budget_version','approved_at']);
        });
    }
};


