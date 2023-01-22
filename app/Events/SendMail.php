<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $users, $details;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($users, $details)
    {
        $this->users   = $users;
        $this->details = $details;
    }

    public function broadcastOn()
    {
        return [];
    }
}
