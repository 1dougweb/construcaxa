<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';

    const TRANSACTION_TYPE_ACCOUNT_PAYABLE = 'account_payable';
    const TRANSACTION_TYPE_ACCOUNT_RECEIVABLE = 'account_receivable';
    const TRANSACTION_TYPE_INVOICE = 'invoice';
    const TRANSACTION_TYPE_RECEIPT = 'receipt';

    protected $fillable = [
        'transaction_type',
        'transaction_id',
        'type',
        'amount',
        'transaction_date',
        'project_id',
        'description',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRelatedTransactionAttribute()
    {
        return match($this->transaction_type) {
            self::TRANSACTION_TYPE_ACCOUNT_PAYABLE => AccountPayable::find($this->transaction_id),
            self::TRANSACTION_TYPE_ACCOUNT_RECEIVABLE => AccountReceivable::find($this->transaction_id),
            self::TRANSACTION_TYPE_INVOICE => Invoice::find($this->transaction_id),
            self::TRANSACTION_TYPE_RECEIPT => Receipt::find($this->transaction_id),
            default => null,
        };
    }
}
