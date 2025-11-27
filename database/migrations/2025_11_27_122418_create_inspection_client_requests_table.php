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
        Schema::create('inspection_client_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained('inspections')->onDelete('cascade');
            $table->unsignedBigInteger('inspection_environment_item_id')->nullable();
            $table->unsignedBigInteger('inspection_item_sub_item_id')->nullable();
            $table->enum('request_type', ['alter_quality', 'add_observation', 'request_change', 'other'])->default('other');
            $table->text('message');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            
            // Foreign keys com nomes mais curtos
            $table->foreign('inspection_environment_item_id', 'icr_env_item_fk')
                ->references('id')
                ->on('inspection_environment_items')
                ->onDelete('cascade');
            
            $table->foreign('inspection_item_sub_item_id', 'icr_sub_item_fk')
                ->references('id')
                ->on('inspection_item_sub_items')
                ->onDelete('cascade');
            
            $table->index('inspection_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_client_requests');
    }
};
