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
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'hourly_rate')) {
                $table->dropColumn('hourly_rate');
            }
            if (Schema::hasColumn('employees', 'monthly_salary')) {
                $table->dropColumn('monthly_salary');
            }
            if (Schema::hasColumn('employees', 'expected_daily_hours')) {
                $table->dropColumn('expected_daily_hours');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('notes');
            $table->decimal('monthly_salary', 10, 2)->nullable()->after('hourly_rate');
            $table->decimal('expected_daily_hours', 4, 2)->default(8.00)->after('monthly_salary');
        });
    }
};
