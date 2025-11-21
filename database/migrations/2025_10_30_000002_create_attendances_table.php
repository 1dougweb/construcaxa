<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entry', 'exit']);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->timestamp('punched_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'punched_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};


