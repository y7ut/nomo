<?php

namespace App\Listeners;

use App\Events\TaskDel;
use App\Notify;
use App\Post;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskDelListener
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
     * @param  TaskDel  $event
     * @return void
     */
    public function handle(TaskDel $event)
    {
        //
        $user = $event->post->user;
        $post = $event->post;
        $notify =new Notify();
        $notify->content = '文章“'.$post->title.'”未能及时整改，已经被系统删除。';
        $notify->user_id = $user->id;
        $notify->post_id = 0;
        $notify->read_status  = 0;
        $notify->type  = 1;
        $notify->save();
//        $post->forceDelete();
        Post::destroy($post->id);
    }
}
