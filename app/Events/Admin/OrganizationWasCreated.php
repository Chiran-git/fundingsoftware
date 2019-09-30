<?php

namespace App\Events\Admin;

use App\User;
use App\Organization;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrganizationWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Organization that was created
     *
     * @var \App\Organization
     */
    public $user;

    /**
     * Organization that was created
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        Organization $organization
    )
    {
        $this->user = $user;
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
