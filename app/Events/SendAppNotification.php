<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendAppNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $data;

    protected $branchId;

    /**
     * Create a new event instance.
     */
    public function __construct($data, $branchId)
    {
        $this->data = $data;
        $this->branchId = $branchId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('app.notifications.branch.' . $this->branchId),
            new Channel('app.notifications.branch.' . $this->branchId),

            new PrivateChannel('app.notifications'),
            new Channel('app.notifications'),
        ];
    }

    public function broadcastWith()
    {
        return [
            'data' => $this->data,
        ];
    }
}
