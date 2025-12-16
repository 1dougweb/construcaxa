<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'cost_price',
        'sale_price',
        'stock',
        'min_stock',
        'supplier_id',
        'category_id',
        'measurement_unit',
        'unit_label',
        'photos'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'photos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Constantes para as unidades de medida
    const UNIT_TYPES = [
        'unit' => [
            'label' => 'Unidade',
            'unit' => 'un'
        ],
        'weight' => [
            'label' => 'Peso',
            'unit' => 'kg'
        ],
        'length' => [
            'label' => 'Metragem',
            'unit' => 'm'
        ]
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function materialRequestItems()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function reservations()
    {
        return $this->hasMany(ProductReservation::class);
    }

    // Acessor para o status do estoque
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= $this->min_stock) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    // Escopo para filtrar produtos com estoque baixo
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }

    // Método para obter a primeira foto
    public function getFirstPhotoAttribute()
    {
        if ($this->photos && is_array($this->photos) && count($this->photos) > 0) {
            return $this->photos[0];
        }
        return null;
    }

    // Método para obter URLs das fotos
    public function getPhotoUrlsAttribute(): array
    {
        if (!$this->photos || !is_array($this->photos)) {
            return [];
        }

        return array_map(function ($photo) {
            if (empty($photo)) {
                return null;
            }
            // Se for caminho antigo (images/products), manter compatibilidade
            if (strpos($photo, 'images/products/') === 0) {
                return '/' . ltrim($photo, '/');
            }
            // Se for caminho do storage (products/...), usar asset() com /storage/
            return asset('storage/' . $photo);
        }, $this->photos);
    }

    // Método para obter a URL da primeira foto
    public function getFirstPhotoUrlAttribute(): ?string
    {
        $urls = $this->photo_urls;
        return !empty($urls) ? $urls[0] : null;
    }

    // Escopo para filtrar produtos sem estoque
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    // Escopo para filtrar por tipo de medida
    public function scopeByMeasurementUnit($query, $unit)
    {
        return $query->where('measurement_unit', $unit);
    }

    /**
     * Get total reserved quantity for active budgets (pending, under_review, approved)
     */
    public function getReservedQuantityAttribute(): float
    {
        return (float) ($this->reservations()
            ->whereHas('projectBudget', function($query) {
                $query->whereIn('status', [
                    \App\Models\ProjectBudget::STATUS_PENDING,
                    \App\Models\ProjectBudget::STATUS_UNDER_REVIEW,
                    \App\Models\ProjectBudget::STATUS_APPROVED
                ]);
            })
            ->sum('quantity_reserved') ?? 0);
    }

    /**
     * Get available stock (physical stock minus reserved)
     */
    public function getAvailableStockAttribute(): float
    {
        return max($this->stock - $this->reserved_quantity, 0);
    }
}
