<?php

namespace App\Events;

use App\Board;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BoardAttention
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $board;
    /**
     * Create a new event instance.
     * @param User $user
     * @param Board $board
     *
     */
    public function __construct(User $user,Board $board)
    {
        //
        $this->user = $user;
        $this->board = $board;
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
