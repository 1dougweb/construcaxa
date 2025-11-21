<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'project_id',
        'budget_id',
        'contract_number',
        'title',
        'description',
        'start_date',
        'end_date',
        'value',
        'status',
        'file_path',
        'signed_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'value' => 'decimal:2',
        'signed_at' => 'datetime',
    ];

    // Relacionamentos
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(ProjectBudget::class, 'budget_id');
    }

    // Accessors
    public function getFormattedValueAttribute(): ?string
    {
        if (!$this->value) {
            return null;
        }

        return 'R$ ' . number_format($this->value, 2, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Rascunho',
            'active' => 'Ativo',
            'expired' => 'Expirado',
            'cancelled' => 'Cancelado',
            default => 'Desconhecido',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'active' => 'bg-green-100 text-green-800',
            'expired' => 'bg-orange-100 text-orange-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Métodos estáticos
    public static function generateContractNumber(): string
    {
        $year = date('Y');
        $lastContract = self::where('contract_number', 'like', "CT-{$year}-%")
            ->orderBy('contract_number', 'desc')
            ->first();

        if ($lastContract) {
            $lastNumber = (int) substr($lastContract->contract_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('CT-%s-%04d', $year, $newNumber);
    }
}
