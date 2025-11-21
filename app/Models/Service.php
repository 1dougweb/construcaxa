<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    // Unit type constants
    const UNIT_HOUR = 'hour';
    const UNIT_FIXED = 'fixed';
    const UNIT_PER_UNIT = 'per_unit';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'unit_type',
        'default_price',
        'minimum_price',
        'maximum_price',
        'is_active',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'minimum_price' => 'decimal:2',
        'maximum_price' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all available unit types
     */
    public static function getUnitTypes(): array
    {
        return [
            self::UNIT_HOUR => 'Por Hora',
            self::UNIT_FIXED => 'Preço Fixo',
            self::UNIT_PER_UNIT => 'Por Unidade',
        ];
    }

    /**
     * Get the service category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    /**
     * Get budget items that use this service
     */
    public function budgetItems(): HasMany
    {
        return $this->hasMany(ProjectBudgetItem::class, 'service_id');
    }

    /**
     * Scope to get only active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the unit type label
     */
    public function getUnitTypeLabelAttribute(): string
    {
        return self::getUnitTypes()[$this->unit_type] ?? ucfirst($this->unit_type);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        $price = number_format($this->default_price, 2, ',', '.');
        
        return match($this->unit_type) {
            self::UNIT_HOUR => "R$ {$price}/hora",
            self::UNIT_FIXED => "R$ {$price}",
            self::UNIT_PER_UNIT => "R$ {$price}/unidade",
            default => "R$ {$price}",
        };
    }

    /**
     * Validate price against minimum and maximum
     */
    public function validatePrice(float $price): bool
    {
        if ($this->minimum_price && $price < $this->minimum_price) {
            return false;
        }
        
        if ($this->maximum_price && $price > $this->maximum_price) {
            return false;
        }
        
        return true;
    }

    /**
     * Calculate total cost based on quantity/hours
     */
    public function calculateCost(float $quantity, ?float $customPrice = null): float
    {
        $price = $customPrice ?? $this->default_price;
        
        return match($this->unit_type) {
            self::UNIT_HOUR => $quantity * $price,
            self::UNIT_FIXED => $price, // Fixed price regardless of quantity
            self::UNIT_PER_UNIT => $quantity * $price,
            default => $quantity * $price,
        };
    }
}
