<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBudget extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'project_id', 'client_id', 'version', 'subtotal', 'discount', 'total', 'status', 'sent_at', 'approved_at', 'approved_by', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'sent_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get all available status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_UNDER_REVIEW => 'Em Análise',
            self::STATUS_APPROVED => 'Aprovado',
            self::STATUS_REJECTED => 'Recusado',
            self::STATUS_CANCELLED => 'Cancelado',
        ];
    }

    /**
     * Get status color for UI display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_APPROVED => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_REJECTED => 'bg-orange-100 text-orange-800 border-orange-200',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800 border-red-200',
            self::STATUS_UNDER_REVIEW => 'bg-blue-100 text-blue-800 border-blue-200',
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? ucfirst($this->status);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProjectBudgetItem::class, 'budget_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'budget_id');
    }
}
