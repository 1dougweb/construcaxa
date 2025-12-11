<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $productId;
    public string $action;
    public string $message;
    public string $productName;

    public function __construct(int $productId, string $action, string $message, string $productName)
    {
        $this->productId = $productId;
        $this->action = $action;
        $this->message = $message;
        $this->productName = $productName;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('products'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'productId' => $this->productId,
            'action' => $this->action,
            'message' => $this->message,
            'productName' => $this->productName,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}

