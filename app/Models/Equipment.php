<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($equipment) {
            if (empty($equipment->slug)) {
                $equipment->slug = static::generateUniqueSlug($equipment->name);
            }
        });

        static::updating(function ($equipment) {
            if ($equipment->isDirty('name')) {
                $equipment->slug = static::generateUniqueSlug($equipment->name, $equipment->id);
            }
        });
    }

    protected static function generateUniqueSlug($name, $excludeId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->when($excludeId, function ($query) use ($excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

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
            // Retornar URL direta de /images/equipment
            return '/' . ltrim($photo, '/');
        }, $this->photos);
    }

    // Método para obter a URL da primeira foto
    public function getFirstPhotoUrlAttribute(): ?string
    {
        $urls = $this->photo_urls;
        return !empty($urls) ? $urls[0] : null;
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

    // Método para obter a última requisição de empréstimo completada
    public function getLastLoan()
    {
        return $this->equipmentRequestItems()
            ->whereHas('equipmentRequest', function($q) {
                $q->where('type', 'loan')
                  ->where('status', 'completed');
            })
            ->with(['equipmentRequest.employee.user'])
            ->orderBy('created_at', 'desc')
            ->first();
    }
}