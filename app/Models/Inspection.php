<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'number',
        'version',
        'inspection_date',
        'address',
        'latitude',
        'longitude',
        'description',
        'inspector_id',
        'user_id',
        'status',
        'photos',
        'pdf_path',
        'qr_code_path',
        'public_token',
        'signed_document_path',
        'approved_at',
        'approved_by',
        'budget_id',
        'notes',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'approved_at' => 'datetime',
        'photos' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(ProjectBudget::class, 'budget_id');
    }

    public function environments(): HasMany
    {
        return $this->hasMany(InspectionEnvironment::class)->orderBy('sort_order');
    }

    public function clientRequests(): HasMany
    {
        return $this->hasMany(InspectionClientRequest::class)->orderBy('created_at', 'desc');
    }

    public static function generateNumber(): string
    {
        $year = date('Y');
        $prefix = "VIST{$year}";
        
        $lastInspection = static::where('number', 'like', "{$prefix}%")
            ->orderByRaw('CAST(SUBSTRING(number, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
            ->first();
        
        if ($lastInspection && preg_match('/VIST\d{4}(\d+)/', $lastInspection->number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function generatePublicToken(): string
    {
        if (!$this->public_token) {
            $this->public_token = bin2hex(random_bytes(32));
            $this->save();
        }
        return $this->public_token;
    }

    public function getPublicUrlAttribute(): string
    {
        if (!$this->public_token) {
            $this->generatePublicToken();
        }
        return route('inspections.public', $this->public_token);
    }
}
