<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class Inspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'number',
        'version',
        'inspection_date',
        'address',
        'description',
        'inspector_id',
        'status',
        'photos',
        'pdf_path',
        'signed_document_path',
        'approved_at',
        'approved_by',
        'budget_id',
        'notes',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'photos' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relacionamentos
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(ProjectBudget::class, 'budget_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    // Métodos
    public static function generateNumber(): string
    {
        $lastNumber = self::where('number', 'like', 'VIS-%')->max('number');
        $nextNumber = $lastNumber ? (intval(substr($lastNumber, 4)) + 1) : 1;
        return 'VIS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getNextVersion(): int
    {
        $lastVersion = self::where('client_id', $this->client_id)
            ->where('id', '!=', $this->id ?? 0)
            ->max('version');
        
        return ($lastVersion ?? 0) + 1;
    }

    public function approve($userId): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $userId,
        ]);
    }

    public function generatePDF(): string
    {
        $pdf = Pdf::loadView('inspections.pdf', ['inspection' => $this]);
        
        $filename = 'inspections/' . $this->number . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());
        
        $this->update(['pdf_path' => $filename]);
        
        return $filename;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Rascunho',
            'pending' => 'Pendente',
            'approved' => 'Aprovada',
            'rejected' => 'Rejeitada',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800 border-gray-200',
            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'approved' => 'bg-green-100 text-green-800 border-green-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
