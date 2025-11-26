<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionEnvironmentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_elements',
        'is_active',
    ];

    protected $casts = [
        'default_elements' => 'array',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
