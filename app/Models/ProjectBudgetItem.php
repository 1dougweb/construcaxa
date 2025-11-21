<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectBudgetItem extends Model
{
    use HasFactory;

    // Item type constants
    const TYPE_PRODUCT = 'product';
    const TYPE_SERVICE = 'service';
    const TYPE_LABOR = 'labor';

    protected $fillable = [
        'budget_id', 'item_type', 'product_id', 'service_id', 'labor_type_id',
        'description', 'quantity', 'hours', 'overtime_hours', 'unit_price', 'total',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get all available item types
     */
    public static function getItemTypes(): array
    {
        return [
            self::TYPE_PRODUCT => 'Produto',
            self::TYPE_SERVICE => 'Serviço',
            self::TYPE_LABOR => 'Mão de Obra',
        ];
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(ProjectBudget::class, 'budget_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function laborType(): BelongsTo
    {
        return $this->belongsTo(LaborType::class);
    }

    /**
     * Get the item type label
     */
    public function getItemTypeLabelAttribute(): string
    {
        return self::getItemTypes()[$this->item_type] ?? ucfirst($this->item_type);
    }

    /**
     * Get the related item (product, service, or labor type)
     */
    public function getRelatedItemAttribute()
    {
        return match($this->item_type) {
            self::TYPE_PRODUCT => $this->product,
            self::TYPE_SERVICE => $this->service,
            self::TYPE_LABOR => $this->laborType,
            default => null,
        };
    }

    /**
     * Get the item name based on type
     */
    public function getItemNameAttribute(): string
    {
        $relatedItem = $this->related_item;
        return $relatedItem ? $relatedItem->name : $this->description;
    }

    /**
     * Calculate total based on item type
     */
    public function calculateTotal(): float
    {
        return match($this->item_type) {
            self::TYPE_PRODUCT => $this->quantity * $this->unit_price,
            self::TYPE_SERVICE => $this->service ? 
                $this->service->calculateCost($this->quantity, $this->unit_price) : 
                $this->quantity * $this->unit_price,
            self::TYPE_LABOR => $this->laborType ? 
                $this->laborType->calculateCost($this->hours, $this->overtime_hours) : 
                ($this->hours * $this->unit_price) + ($this->overtime_hours * $this->unit_price * 1.5),
            default => $this->quantity * $this->unit_price,
        };
    }

    /**
     * Get formatted quantity/hours display
     */
    public function getQuantityDisplayAttribute(): string
    {
        return match($this->item_type) {
            self::TYPE_LABOR => $this->hours . 'h' . ($this->overtime_hours > 0 ? ' + ' . $this->overtime_hours . 'h extra' : ''),
            default => number_format($this->quantity, 3, ',', '.'),
        };
    }

    /**
     * Get unit display based on item type
     */
    public function getUnitDisplayAttribute(): string
    {
        if ($this->item_type === self::TYPE_LABOR) {
            return 'horas';
        }
        
        if ($this->item_type === self::TYPE_SERVICE && $this->service) {
            return $this->service->unit_type_label;
        }
        
        if ($this->item_type === self::TYPE_PRODUCT && $this->product) {
            return $this->product->unit_label ?? 'unidade';
        }
        
        return 'unidade';
    }

    /**
     * Scope to filter by item type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('item_type', $type);
    }

    /**
     * Boot method to auto-calculate total
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total = $item->calculateTotal();
        });
    }
}


