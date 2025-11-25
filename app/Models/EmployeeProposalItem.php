<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProposalItem extends Model
{
    use HasFactory;

    const ITEM_TYPE_LABOR = 'labor';
    const ITEM_TYPE_SERVICE = 'service';

    protected $fillable = [
        'proposal_id',
        'item_type',
        'labor_type_id',
        'service_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Calcular total_price automaticamente
            $item->total_price = round($item->quantity * $item->unit_price, 2);
        });
    }

    public static function getItemTypeOptions(): array
    {
        return [
            self::ITEM_TYPE_LABOR => 'Mão de Obra',
            self::ITEM_TYPE_SERVICE => 'Serviço',
        ];
    }

    public function proposal()
    {
        return $this->belongsTo(EmployeeProposal::class);
    }

    public function laborType()
    {
        return $this->belongsTo(LaborType::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getItemTypeLabelAttribute(): string
    {
        return self::getItemTypeOptions()[$this->item_type] ?? ucfirst($this->item_type);
    }

    public function getItemNameAttribute(): string
    {
        if ($this->item_type === self::ITEM_TYPE_LABOR && $this->laborType) {
            return $this->laborType->name;
        }
        if ($this->item_type === self::ITEM_TYPE_SERVICE && $this->service) {
            return $this->service->name;
        }
        return 'Item sem nome';
    }
}

