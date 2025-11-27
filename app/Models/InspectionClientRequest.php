<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionClientRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'inspection_environment_item_id',
        'inspection_item_sub_item_id',
        'request_type',
        'message',
        'status',
        'admin_response',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function environmentItem(): BelongsTo
    {
        return $this->belongsTo(InspectionEnvironmentItem::class, 'inspection_environment_item_id');
    }

    public function subItem(): BelongsTo
    {
        return $this->belongsTo(InspectionItemSubItem::class, 'inspection_item_sub_item_id');
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function getRequestTypeLabelAttribute(): string
    {
        return match($this->request_type) {
            'alter_quality' => 'Alterar Qualidade',
            'add_observation' => 'Adicionar Observação',
            'request_change' => 'Solicitar Alteração',
            'other' => 'Outro',
            default => 'Desconhecido',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            default => 'Desconhecido',
        };
    }
}
