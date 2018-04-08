<?php

namespace App\Listeners;

use App\Events\TaskUpdate;
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
    }
}
