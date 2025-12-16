<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionItemPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_environment_item_id',
        'photo_path',
        'sort_order',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InspectionEnvironmentItem::class, 'inspection_environment_item_id');
    }

    public function getUrlAttribute(): string
    {
        // Se for caminho antigo (images/inspections), manter compatibilidade
        if (strpos($this->photo_path, 'images/inspections/') === 0) {
            return '/' . ltrim($this->photo_path, '/');
        }
        // Se for caminho do storage (inspections/...), usar asset()
        return asset('storage/' . $this->photo_path);
    }
}
