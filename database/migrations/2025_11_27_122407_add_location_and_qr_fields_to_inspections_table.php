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
        Schema::table('inspections', function (Blueprint $table) {
            if (!Schema::hasColumn('inspections', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('address');
            }
            if (!Schema::hasColumn('inspections', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('inspections', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('pdf_path');
            }
            if (!Schema::hasColumn('inspections', 'public_token')) {
                $table->string('public_token')->nullable()->unique()->after('qr_code_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            if (Schema::hasColumn('inspections', 'public_token')) {
                $table->dropUnique(['public_token']);
                $table->dropColumn('public_token');
            }
            if (Schema::hasColumn('inspections', 'qr_code_path')) {
                $table->dropColumn('qr_code_path');
            }
            if (Schema::hasColumn('inspections', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('inspections', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};
