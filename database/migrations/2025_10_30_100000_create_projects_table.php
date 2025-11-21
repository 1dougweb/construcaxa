<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('address')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date_estimated')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'paused', 'completed', 'cancelled'])->default('planned');
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};


