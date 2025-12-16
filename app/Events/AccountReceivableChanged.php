<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountReceivableChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $accountReceivableId;
    public string $action;
    public string $message;
    public array $data;

    public function __construct(int $accountReceivableId, string $action, string $message, array $data = [])
    {
        $this->accountReceivableId = $accountReceivableId;
        $this->action = $action;
        $this->message = $message;
        $this->data = $data;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('financial'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'account-receivable.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'accountReceivableId' => $this->accountReceivableId,
            'action' => $this->action,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
