<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'serial_number',
        'description',
        'photos',
        'status',
        'category_id',
        'equipment_category_id',
        'current_employee_id',
        'purchase_price',
        'purchase_date',
        'notes',
    ];

    protected $casts = [
        'photos' => 'array',
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function equipmentCategory()
    {
        return $this->belongsTo(EquipmentCategory::class);
    }

    public function currentEmployee()
    {
        return $this->belongsTo(Employee::class, 'current_employee_id');
    }

    public function equipmentRequestItems()
    {
        return $this->hasMany(EquipmentRequestItem::class);
    }

    public function movements()
    {
        return $this->hasMany(EquipmentMovement::class);
    }

    // Scope para filtrar por status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope para equipamentos disponíveis
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope para buscar por nome ou número de série
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('serial_number', 'like', "%{$search}%");
        });
    }

    // Método para obter a primeira foto
    public function getFirstPhotoAttribute()
    {
        if ($this->photos && is_array($this->photos) && count($this->photos) > 0) {
            return $this->photos[0];
        }
        return null;
    }

    // Método para verificar se está emprestado
    public function isBorrowed()
    {
        return $this->status === 'borrowed';
    }

    // Método para verificar se está disponível
    public function isAvailable()
    {
        return $this->status === 'available';
    }
}