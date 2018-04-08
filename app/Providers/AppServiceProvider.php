<?php

namespace App\Providers;

use App\Comment;
use App\Observers\CommentObservers;
use App\Observers\OrderObservers;
use App\Observers\PostObservers;
use App\Order;
use App\Post;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \Carbon\Carbon::setLocale('zh');
        Comment::observe(CommentObservers::class);
        Order::observe(OrderObservers::class);
        Post::observe(PostObservers::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
