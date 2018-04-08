<?php

namespace App\Listeners;

use App\Events\TaskFinish;
use App\Notify;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskFinishListener
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
     * @param  TaskFinish  $event
     * @return void
     */
    public function handle(TaskFinish $event)
    {
        //
        $user = $event->user;
        $task = $event->task;
        $point = $task->progress_size()*10;
        $user->increment('integration',$point);
        $notify =new Notify();
        $notify->content = '恭喜完成每日任务：“'.$task->task_content().'”。送你'.$point.'积分';
        $notify->user_id = $user->id;
        $notify->post_id = 0;
        $notify->read_status  = 0;
        $notify->type  = 1;
        $notify->save();
    }
}
