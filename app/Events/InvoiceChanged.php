<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $invoiceId;
    public string $action;
    public string $message;
    public array $data;

    public function __construct(int $invoiceId, string $action, string $message, array $data = [])
    {
        $this->invoiceId = $invoiceId;
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
        return 'invoice.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'invoiceId' => $this->invoiceId,
            'action' => $this->action,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
