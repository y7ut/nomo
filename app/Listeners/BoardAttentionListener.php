<?php

namespace App\Listeners;

use App\Events\BoardAttention;
use App\Notify;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BoardAttentionListener
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
     * @param  BoardAttention  $event
     * @return void
     */
    public function handle(BoardAttention $event)
    {
        //
        $board = $event->board;
        $user = $event->user;
        $user->increment('integration',1);
        $notify =new Notify();
        $notify->content = '欢迎加入'.$board->name.'专栏。送你1积分';
        $notify->user_id = $user->id;
        $notify->post_id = 0;
        $notify->read_status  = 0;
        $notify->type  = 1;
        $notify->save();
    }
}
