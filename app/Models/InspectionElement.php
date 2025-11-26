<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_environment_id',
        'name',
        'technical_notes',
        'condition_status',
        'photos',
        'measurements',
        'defects_identified',
        'probable_causes',
        'sort_order',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    // Relacionamentos
    public function environment(): BelongsTo
    {
        return $this->belongsTo(InspectionEnvironment::class, 'inspection_environment_id');
    }

    // Métodos auxiliares
    public function getConditionLabelAttribute(): string
    {
        return match($this->condition_status) {
            'poor' => 'Ruim',
            'fair' => 'Razoável',
            'good' => 'Em bom estado',
            'very_good' => 'Em ótimo estado',
            'excellent' => 'Excelente',
            default => ucfirst($this->condition_status),
        };
    }

    public function getConditionColorAttribute(): string
    {
        return match($this->condition_status) {
            'poor' => '#DC2626', // vermelho
            'fair' => '#F59E0B', // laranja
            'good' => '#10B981', // verde
            'very_good' => '#3B82F6', // azul
            'excellent' => '#059669', // verde escuro
            default => '#6B7280',
        };
    }
}
