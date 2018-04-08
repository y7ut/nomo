<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Attention' => [
            'App\Listeners\AttentionListener',
        ],
        'App\Events\BoardAttention' => [
            'App\Listeners\BoardAttentionListener',
        ],
        'App\Events\TaskFinish' => [
            'App\Listeners\TaskFinishListener',
        ],
        'App\Events\TaskUpdate' => [
            'App\Listeners\TaskUpdateListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
