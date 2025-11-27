<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionItemSubItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_environment_item_id',
        'title',
        'description',
        'observations',
        'quality_rating',
        'sort_order',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InspectionEnvironmentItem::class, 'inspection_environment_item_id');
    }

    public function clientRequests(): HasMany
    {
        return $this->hasMany(InspectionClientRequest::class, 'inspection_item_sub_item_id');
    }

    public function getQualityLabelAttribute(): string
    {
        return match($this->quality_rating) {
            'excellent' => 'Excelente',
            'very_good' => 'Muito Bom',
            'good' => 'Bom',
            'poor' => 'Ruim',
            default => 'Não avaliado',
        };
    }
}
