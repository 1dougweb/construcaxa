<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'hire_date')) {
                $table->date('hire_date')->nullable();
            }
            if (!Schema::hasColumn('employees', 'birth_date')) {
                $table->date('birth_date')->nullable();
            }
            if (!Schema::hasColumn('employees', 'cpf')) {
                $table->string('cpf')->nullable()->unique();
            }
            if (!Schema::hasColumn('employees', 'rg')) {
                $table->string('rg')->nullable()->unique();
            }
            if (!Schema::hasColumn('employees', 'document_id')) {
                $table->string('document_id')->nullable();
            }
            if (!Schema::hasColumn('employees', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('employees', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }
            if (!Schema::hasColumn('employees', 'document_file')) {
                $table->string('document_file')->nullable();
            }
            if (!Schema::hasColumn('employees', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable();
            }
            if (!Schema::hasColumn('employees', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'hire_date')) {
                $table->dropColumn('hire_date');
            }
            if (Schema::hasColumn('employees', 'birth_date')) {
                $table->dropColumn('birth_date');
            }
            if (Schema::hasColumn('employees', 'cpf')) {
                $table->dropColumn('cpf');
            }
            if (Schema::hasColumn('employees', 'rg')) {
                $table->dropColumn('rg');
            }
            if (Schema::hasColumn('employees', 'document_id')) {
                $table->dropColumn('document_id');
            }
            if (Schema::hasColumn('employees', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('employees', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
            if (Schema::hasColumn('employees', 'document_file')) {
                $table->dropColumn('document_file');
            }
            if (Schema::hasColumn('employees', 'emergency_contact')) {
                $table->dropColumn('emergency_contact');
            }
            if (Schema::hasColumn('employees', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};


