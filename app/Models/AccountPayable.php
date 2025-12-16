<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountPayable extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'supplier_id',
        'project_id',
        'number',
        'description',
        'category',
        'amount',
        'due_date',
        'paid_date',
        'status',
        'notes',
        'document_file',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_PAID => 'Paga',
            self::STATUS_OVERDUE => 'Vencida',
            self::STATUS_CANCELLED => 'Cancelada',
        ];
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PAID => 'bg-green-100 text-green-800 border-green-200',
            self::STATUS_OVERDUE => 'bg-red-100 text-red-800 border-red-200',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800 border-gray-200',
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? ucfirst($this->status);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generateNumber(): string
    {
        $year = date('Y');
        $prefix = "CP{$year}";
        
        $lastPayable = static::where('number', 'like', "{$prefix}%")
            ->orderByRaw('CAST(SUBSTRING(number, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();
        
        if ($lastPayable && preg_match('/CP\d{4}(\d+)/', $lastPayable->number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
