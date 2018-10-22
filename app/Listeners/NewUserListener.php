<?php

namespace App\Listeners;

use App\Events\NewUser;
use App\Notify;
use App\Role;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserListener
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
     * @param  NewUser  $event
     * @return void
     */
    public function handle(NewUser $event)
    {
        //
        $godRole = Role::where('name','god')->first();
        $god = $godRole->users()->first();
        $user = $event->user;
        $notify =new Notify();
        $notify->content = '新的用户'.$user->name.'加入了！';
        $notify->user_id = $god->id;
        $notify->post_id = 0;
        $notify->read_status  = 0;
        $notify->type  = 1;
        $notify->save();
    }
}
