<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'client_name',
        'description',
        'status',
        'total_amount',
        'user_id',
        'notes',
        'completion_date',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'completion_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scope para filtrar por status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope para buscar por número ou cliente
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('number', 'like', "%{$search}%")
              ->orWhere('client_name', 'like', "%{$search}%");
        });
    }
}