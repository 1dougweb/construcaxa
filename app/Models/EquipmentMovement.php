<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'equipment_id',
        'employee_id',
        'equipment_request_id',
        'type',
        'notes',
        'condition_before',
        'condition_after',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function equipmentRequest()
    {
        return $this->belongsTo(EquipmentRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para filtrar por tipo
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope para filtrar por equipamento
    public function scopeByEquipment($query, $equipmentId)
    {
        return $query->where('equipment_id', $equipmentId);
    }

    // Scope para filtrar por funcionário
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
}