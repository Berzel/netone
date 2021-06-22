<?php

namespace App\Events;

use App\Models\EcocashPayment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EcocashPaymentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The ecocash payment instance
     *
     * @var EcocashPayment
     */
    public EcocashPayment $ecocashPayment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(EcocashPayment $ecocashPayment)
    {
        $this->ecocashPayment = $ecocashPayment;
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
