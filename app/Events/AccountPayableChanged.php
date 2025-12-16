<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AccountPayableChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $accountPayableId;
    public string $action;
    public string $message;
    public array $data;

    public function __construct(int $accountPayableId, string $action, string $message, array $data = [])
    {
        $this->accountPayableId = $accountPayableId;
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
        return 'account-payable.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'accountPayableId' => $this->accountPayableId,
            'action' => $this->action,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
