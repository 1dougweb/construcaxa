<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionEnvironmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_environment_id',
        'title',
        'sort_order',
    ];

    public function environment(): BelongsTo
    {
        return $this->belongsTo(InspectionEnvironment::class, 'inspection_environment_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InspectionItemPhoto::class)->orderBy('sort_order');
    }

    public function subItems(): HasMany
    {
        return $this->hasMany(InspectionItemSubItem::class)->orderBy('sort_order');
    }

    public function clientRequests(): HasMany
    {
        return $this->hasMany(InspectionClientRequest::class, 'inspection_environment_item_id');
    }
}
