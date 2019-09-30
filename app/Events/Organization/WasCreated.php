<?php

namespace App\Events\Organization;

use App\Organization;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Organization that was created
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Create a new event instance.
     *
     * @param \App\Organization $organization Organization that was created
     *
     * @return void
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
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
