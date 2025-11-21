<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_request_id',
        'equipment_id',
        'quantity',
        'condition_notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function equipmentRequest()
    {
        return $this->belongsTo(EquipmentRequest::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}