<?php

namespace App\Events;

use App\Models\Topup;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TopupFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The Topup instance
     *
     * @var Topup
     */
    public Topup $topup;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Topup $topup)
    {
        $this->topup = $topup;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
