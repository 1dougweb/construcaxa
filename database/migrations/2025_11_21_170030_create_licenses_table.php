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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_token')->unique();
            $table->string('license_server_url')->nullable();
            $table->string('device_id')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('is_valid')->default(false);
            $table->timestamp('last_validated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('validation_error')->nullable();
            $table->json('license_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
