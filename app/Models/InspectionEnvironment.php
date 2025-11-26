<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionEnvironment extends Model
{
    use HasFactory;

    protected $fillable = [
        'technical_inspection_id',
        'name',
        'technical_notes',
        'photos',
        'videos',
        'measurements',
        'google_drive_link',
        'qr_code_path',
        'sort_order',
    ];

    protected $casts = [
        'photos' => 'array',
        'videos' => 'array',
    ];

    // Relacionamentos
    public function technicalInspection(): BelongsTo
    {
        return $this->belongsTo(TechnicalInspection::class);
    }

    public function elements(): HasMany
    {
        return $this->hasMany(InspectionElement::class)->orderBy('sort_order');
    }
}
