<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'employee_id',
        'service_order_id',
        'type',
        'status',
        'purpose',
        'notes',
        'expected_return_date',
        'actual_return_date',
        'user_id',
    ];

    protected $casts = [
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
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

    // Scope para filtrar por tipo
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Scope para buscar por número
    public function scopeSearch($query, $search)
    {
        return $query->where('number', 'like', "%{$search}%");
    }

    // Método para processar empréstimo de equipamentos
    public function processLoan()
    {
        if ($this->type !== 'loan' || $this->status !== 'approved') {
            throw new \Exception('Requisição deve ser do tipo empréstimo e estar aprovada');
        }

        foreach ($this->items as $item) {
            $equipment = $item->equipment;
            
            if (!$equipment->isAvailable()) {
                throw new \Exception("Equipamento {$equipment->name} não está disponível");
            }

            // Atualizar status do equipamento
            $equipment->update([
                'status' => 'borrowed',
                'current_employee_id' => $this->employee_id,
            ]);

            // Registrar movimento
            EquipmentMovement::create([
                'equipment_id' => $equipment->id,
                'employee_id' => $this->employee_id,
                'equipment_request_id' => $this->id,
                'type' => 'loan',
                'notes' => "Empréstimo via requisição #{$this->number}",
                'user_id' => auth()->id(),
            ]);
        }

        $this->update(['status' => 'completed']);
    }

    // Método para processar devolução de equipamentos
    public function processReturn()
    {
        if ($this->type !== 'return' || $this->status !== 'approved') {
            throw new \Exception('Requisição deve ser do tipo devolução e estar aprovada');
        }

        foreach ($this->items as $item) {
            $equipment = $item->equipment;
            
            if (!$equipment->isBorrowed()) {
                throw new \Exception("Equipamento {$equipment->name} não está emprestado");
            }

            // Atualizar status do equipamento
            $equipment->update([
                'status' => 'available',
                'current_employee_id' => null,
            ]);

            // Registrar movimento
            EquipmentMovement::create([
                'equipment_id' => $equipment->id,
                'employee_id' => $this->employee_id,
                'equipment_request_id' => $this->id,
                'type' => 'return',
                'notes' => "Devolução via requisição #{$this->number}",
                'condition_after' => $item->condition_notes,
                'user_id' => auth()->id(),
            ]);
        }

        $this->update([
            'status' => 'completed',
            'actual_return_date' => now(),
        ]);
    }
}