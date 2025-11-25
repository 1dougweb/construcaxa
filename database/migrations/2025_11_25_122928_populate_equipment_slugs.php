<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Equipment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $equipment = Equipment::whereNull('slug')->get();
        
        foreach ($equipment as $item) {
            $baseSlug = Str::slug($item->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Garantir que o slug seja único
            while (Equipment::where('slug', $slug)->where('id', '!=', $item->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $item->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não há necessidade de reverter, pois os slugs serão removidos na migration anterior
    }
};
