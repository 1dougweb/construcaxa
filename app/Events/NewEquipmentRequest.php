<?php

namespace App\Events;

use App\Models\EquipmentRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewEquipmentRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $equipmentRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(EquipmentRequest $equipmentRequest)
    {
        $this->equipmentRequest = $equipmentRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('equipment-requests'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'equipment-request.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'request' => [
                'id' => $this->equipmentRequest->id,
                'number' => $this->equipmentRequest->number,
                'customer_name' => $this->equipmentRequest->customer_name,
                'status' => $this->equipmentRequest->status,
                'created_at' => $this->equipmentRequest->created_at->toIso8601String(),
            ],
            'message' => "Nova requisição de equipamento #{$this->equipmentRequest->number} criada",
            'timestamp' => now()->toIso8601String(),
        ];
    }
}



