<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_PIX = 'pix';
    const PAYMENT_METHOD_CREDIT_CARD = 'credit_card';
    const PAYMENT_METHOD_DEBIT_CARD = 'debit_card';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_METHOD_CHECK = 'check';
    const PAYMENT_METHOD_OTHER = 'other';

    protected $fillable = [
        'number',
        'project_id',
        'client_id',
        'invoice_id',
        'issue_date',
        'amount',
        'payment_method',
        'description',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function getPaymentMethodOptions(): array
    {
        return [
            self::PAYMENT_METHOD_CASH => 'Dinheiro',
            self::PAYMENT_METHOD_PIX => 'PIX',
            self::PAYMENT_METHOD_CREDIT_CARD => 'Cartão de Crédito',
            self::PAYMENT_METHOD_DEBIT_CARD => 'Cartão de Débito',
            self::PAYMENT_METHOD_BANK_TRANSFER => 'Transferência Bancária',
            self::PAYMENT_METHOD_CHECK => 'Cheque',
            self::PAYMENT_METHOD_OTHER => 'Outro',
        ];
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::getPaymentMethodOptions()[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generateNumber(): string
    {
        $year = date('Y');
        $prefix = "REC{$year}";
        
        $lastReceipt = static::where('number', 'like', "{$prefix}%")
            ->orderByRaw('CAST(SUBSTRING(number, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();
        
        if ($lastReceipt && preg_match('/REC\d{4}(\d+)/', $lastReceipt->number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
