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
        Schema::table('inspection_environment_items', function (Blueprint $table) {
            if (Schema::hasColumn('inspection_environment_items', 'quality_rating')) {
                $table->dropColumn('quality_rating');
            }
            if (Schema::hasColumn('inspection_environment_items', 'observations')) {
                $table->dropColumn('observations');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_environment_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inspection_environment_items', 'quality_rating')) {
                $table->enum('quality_rating', ['excellent', 'good', 'regular', 'poor'])->default('good')->after('title');
            }
            if (!Schema::hasColumn('inspection_environment_items', 'observations')) {
                $table->text('observations')->nullable()->after('quality_rating');
            }
        });
    }
};
