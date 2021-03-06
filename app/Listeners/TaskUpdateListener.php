<?php

namespace App\Listeners;

use App\Events\TaskUpdate;
use App\Notify;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskUpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TaskUpdate  $event
     * @return void
     */
    public function handle(TaskUpdate $event)
    {
        //
        $user = $event->task->user();
        $task = $event->task;
        $notify =new Notify();
        $notify->content = '整改文章“'.$task->post()->title.'”成功。';
        $notify->user_id = $user->id;
        $notify->post_id = $task->post()->id;
        $notify->read_status  = 0;
        $notify->type  = 0;
        $notify->save();
    }
}
