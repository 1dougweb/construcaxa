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
        'inspection_id',
        'template_id',
        'name',
        'sort_order',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InspectionEnvironmentTemplate::class, 'template_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InspectionEnvironmentItem::class)->orderBy('sort_order');
    }
}
