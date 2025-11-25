<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EmployeeProposal extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    const CONTRACT_TYPE_FIXED_DAYS = 'fixed_days';
    const CONTRACT_TYPE_INDEFINITE = 'indefinite';

    protected $fillable = [
        'employee_id',
        'project_id',
        'hourly_rate',
        'contract_type',
        'days',
        'start_date',
        'end_date',
        'status',
        'token',
        'observations',
        'total_amount',
        'accepted_at',
        'rejected_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'days' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proposal) {
            if (empty($proposal->token)) {
                $proposal->token = static::generateUniqueToken();
            }
            if (empty($proposal->status)) {
                $proposal->status = self::STATUS_PENDING;
            }
            if (empty($proposal->expires_at)) {
                // Expira em 30 dias por padrão
                $proposal->expires_at = now()->addDays(30);
            }
        });

        static::saving(function ($proposal) {
            // Calcular total_amount baseado nos itens
            if ($proposal->exists) {
                $proposal->total_amount = $proposal->items()->sum('total_price');
            }
        });
    }

    public static function generateUniqueToken(): string
    {
        do {
            $token = Str::random(64);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_ACCEPTED => 'Aceita',
            self::STATUS_REJECTED => 'Rejeitada',
            self::STATUS_EXPIRED => 'Expirada',
        ];
    }

    public static function getContractTypeOptions(): array
    {
        return [
            self::CONTRACT_TYPE_FIXED_DAYS => 'Dias Determinados',
            self::CONTRACT_TYPE_INDEFINITE => 'Indeterminado',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(EmployeeProposalItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? ucfirst($this->status);
    }

    public function getContractTypeLabelAttribute(): string
    {
        return self::getContractTypeOptions()[$this->contract_type] ?? ucfirst($this->contract_type);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACCEPTED => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800 border-red-200',
            self::STATUS_EXPIRED => 'bg-gray-100 text-gray-800 border-gray-200',
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || 
               ($this->expires_at && $this->expires_at->isPast());
    }

    public function accept(): void
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);
    }

    public function reject(): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
        ]);
    }

    public function calculateTotalAmount(): float
    {
        return $this->items()->sum('total_price');
    }
}

