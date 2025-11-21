<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'punched_date')) {
                $table->date('punched_date')->after('punched_at');
            }
        });

        // Add unique constraint per user per day per type
        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['user_id', 'punched_date', 'type'], 'att_user_date_type_unique');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('att_user_date_type_unique');
            if (Schema::hasColumn('attendances', 'punched_date')) {
                $table->dropColumn('punched_date');
            }
        });
    }
};


