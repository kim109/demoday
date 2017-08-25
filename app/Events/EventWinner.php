<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventWinner implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $winner;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($winner)
    {
        $this->winner = $winner;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('demoday');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->winner->username ?? null,
            'name' => $this->winner->name ?? null
        ];
    }
}
