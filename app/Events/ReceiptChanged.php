<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReceiptChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $receiptId;
    public string $action;
    public string $message;
    public array $data;

    public function __construct(int $receiptId, string $action, string $message, array $data = [])
    {
        $this->receiptId = $receiptId;
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
        return 'receipt.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'receiptId' => $this->receiptId,
            'action' => $this->action,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
