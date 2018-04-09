<?php

namespace App\Console\Commands;

use App\Events\TaskDel;
use App\Post;
use Illuminate\Console\Command;

class DeleteNotEditPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nomo:dnep';
    protected $redis;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Not Edit Posts in Redis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->redis = app('redis.connection');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->redis->subscribe(['__keyevent@0__:expired'], function ($message) {
//            $test = 'Nomo:Task:Admin_post:2:28';

            $post_id = substr($message,23,2);
            echo $post_id;
            if(substr($message,0,20)=='Nomo:Task:Admin_post'){
                event(new TaskDel(Post::find($post_id)));
            }
        });
    }
}
