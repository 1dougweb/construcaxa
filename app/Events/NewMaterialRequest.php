<?php

namespace App\Events;

use App\Models\MaterialRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMaterialRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $materialRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(MaterialRequest $materialRequest)
    {
        $this->materialRequest = $materialRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('material-requests'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'material-request.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'request' => [
                'id' => $this->materialRequest->id,
                'number' => $this->materialRequest->number,
                'customer_name' => $this->materialRequest->customer_name,
                'total_amount' => $this->materialRequest->total_amount,
                'has_stock_out' => $this->materialRequest->has_stock_out,
                'created_at' => $this->materialRequest->created_at->toIso8601String(),
            ],
            'message' => "Nova requisição de material #{$this->materialRequest->number} criada",
            'timestamp' => now()->toIso8601String(),
        ];
    }
}



