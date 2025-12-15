<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'project_budget_id',
        'quantity_reserved',
    ];

    protected $casts = [
        'quantity_reserved' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function projectBudget(): BelongsTo
    {
        return $this->belongsTo(ProjectBudget::class, 'project_budget_id');
    }
}
