<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para notificações não lidas
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para notificações lidas
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Verifica se a notificação foi lida
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Marca a notificação como lida
     */
    public function markAsRead(): bool
    {
        if ($this->isRead()) {
            return false;
        }

        return $this->update(['read_at' => now()]);
    }

    /**
     * Marca a notificação como não lida
     */
    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }
}
