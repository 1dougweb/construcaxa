<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TechnicalInspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'inspection_date',
        'address',
        'unit_area',
        'furniture_status',
        'map_image_path',
        'coordinates',
        'responsible_name',
        'involved_parties',
        'total_photos_count',
        'status',
        'user_id',
        'client_id',
        'project_id',
        'pdf_path',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'coordinates' => 'array',
        'unit_area' => 'decimal:2',
        'total_photos_count' => 'integer',
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function environments(): HasMany
    {
        return $this->hasMany(InspectionEnvironment::class)->orderBy('sort_order');
    }

    // Métodos auxiliares
    public static function generateNumber(): string
    {
        $year = date('Y');
        $lastNumber = self::where('number', 'like', "VIS-{$year}-%")
            ->max('number');
        
        if ($lastNumber) {
            $lastSequence = (int) substr($lastNumber, -4);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }
        
        return sprintf('VIS-%s-%04d', $year, $nextSequence);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Rascunho',
            'in_progress' => 'Em Preenchimento',
            'completed' => 'Finalizada',
            'archived' => 'Arquivada',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'archived' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function updateTotalPhotosCount(): void
    {
        $total = 0;
        
        foreach ($this->environments as $environment) {
            if ($environment->photos) {
                $total += count($environment->photos);
            }
            
            foreach ($environment->elements as $element) {
                if ($element->photos) {
                    $total += count($element->photos);
                }
            }
        }
        
        $this->update(['total_photos_count' => $total]);
    }
}
