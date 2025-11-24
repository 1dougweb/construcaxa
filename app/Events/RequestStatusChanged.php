<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $requestId;
    public $requestType;
    public $oldStatus;
    public $newStatus;
    public $requestNumber;

    /**
     * Create a new event instance.
     */
    public function __construct($requestId, $requestType, $oldStatus, $newStatus, $requestNumber = null)
    {
        $this->requestId = $requestId;
        $this->requestType = $requestType; // 'material' or 'equipment'
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->requestNumber = $requestNumber;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channel = $this->requestType === 'material' 
            ? 'material-requests' 
            : 'equipment-requests';
            
        return [
            new Channel($channel),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'request.status-changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $requestTypeLabel = $this->requestType === 'material' ? 'requisição de material' : 'requisição de equipamento';
        
        return [
            'request_id' => $this->requestId,
            'request_type' => $this->requestType,
            'request_number' => $this->requestNumber,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "Status da {$requestTypeLabel} #{$this->requestNumber} alterado de '{$this->oldStatus}' para '{$this->newStatus}'",
            'timestamp' => now()->toIso8601String(),
        ];
    }
}



