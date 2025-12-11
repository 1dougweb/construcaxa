<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionEnvironmentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'description',
        'default_elements',
        'is_active',
    ];

    protected $casts = [
        'default_elements' => 'array',
        'is_active' => 'boolean',
    ];

    public function environments(): HasMany
    {
        return $this->hasMany(InspectionEnvironment::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
