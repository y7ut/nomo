<?php

namespace App\Events;

use App\Tasks\EditPostAdminTask;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskUpdate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $task;

    /**
     * Create a new event instance.
     *
     * @param DailyTask $task
     *
     */
    public function __construct(EditPostAdminTask $task)
    {
        //

        $this->task = $task;
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
