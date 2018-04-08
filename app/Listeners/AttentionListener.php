<?php

namespace App\Listeners;

use App\Events\Attention;
use App\Notify;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttentionListener
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
     * @param  Attention  $event
     * @return void
     */
    public function handle(Attention $event)
    {
        //


        $post = $event->post;
        $user = $event->user;
        $post->user->increment('integration',5);
        $notify =new Notify();
        $notify->content = $user->name.'关注了你的文章《'.$post->title.'》。积分+5';
        $notify->user_id = $post->user->id;
        $notify->post_id = $post->id;
        $notify->read_status  = 0;
        $notify->type  = 0;
        $notify->save();
    }
}
