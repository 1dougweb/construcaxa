<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (!Schema::hasColumn('inspections', 'client_decision')) {
                $table->string('client_decision', 20)->nullable()->after('budget_id');
            }
            if (!Schema::hasColumn('inspections', 'client_decision_at')) {
                $table->timestamp('client_decision_at')->nullable()->after('client_decision');
            }
            if (!Schema::hasColumn('inspections', 'client_comment')) {
                $table->text('client_comment')->nullable()->after('client_decision_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (Schema::hasColumn('inspections', 'client_comment')) {
                $table->dropColumn('client_comment');
            }
            if (Schema::hasColumn('inspections', 'client_decision_at')) {
                $table->dropColumn('client_decision_at');
            }
            if (Schema::hasColumn('inspections', 'client_decision')) {
                $table->dropColumn('client_decision');
            }
        });
    }
};


