<?php

namespace App\Events;

use App\Models\StockMovement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockMovementCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stockMovement;

    /**
     * Create a new event instance.
     */
    public function __construct(StockMovement $stockMovement)
    {
        $this->stockMovement = $stockMovement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('stock-movements'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'stock-movement.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'movement' => [
                'id' => $this->stockMovement->id,
                'product_id' => $this->stockMovement->product_id,
                'product_name' => $this->stockMovement->product->name,
                'type' => $this->stockMovement->type,
                'quantity' => $this->stockMovement->quantity,
                'previous_stock' => $this->stockMovement->previous_stock,
                'new_stock' => $this->stockMovement->new_stock,
                'user_name' => $this->stockMovement->user->name ?? 'Sistema',
                'created_at' => $this->stockMovement->created_at->toIso8601String(),
            ],
            'message' => "Movimentação de estoque: {$this->stockMovement->type} de {$this->stockMovement->quantity} unidades de {$this->stockMovement->product->name}",
            'timestamp' => now()->toIso8601String(),
        ];
    }
}

