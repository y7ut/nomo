<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/6
 * Time: 11:37
 */

namespace App\Tasks;


use Illuminate\Foundation\Auth\User;

class TaskBox
{
    protected $redis;
    protected $task_list = ['\App\Tasks\DailyTaskAboutQuestion','\App\Tasks\DailyTaskCommentAttention','\App\Tasks\DailyTaskAboutPost','\App\Tasks\DailyTaskAboutComment'];
    protected $today_list;
    public function __construct(User $user){
        $user_id = $user->id;
        $this->redis = app('redis.connection');
        if($this->redis->llen ('Daily:TaskBox:'.$user_id)==3){
            $this->today_list = $this->redis->lrange ('Daily:TaskBox:'.$user_id, 0, 2);
        }else{
            $second = \Carbon\Carbon::tomorrow()->timestamp-\Carbon\Carbon::now()->timestamp;
            $this->today_list=array_rand($this->task_list,3);
            $task_all = $this->task_list;
            $task_list = collect($this->today_list)->map(function($item) use ($task_all){
                return $task_all[$item];
            });
            $task_list->map(function($task) use($user_id) {
                $this->redis->rpush('Daily:TaskBox:'.$user_id, $task);
            });
            $this->redis->expire('Daily:TaskBox:'.$user_id, $second);
            $this->today_list = $this->redis->lrange ('Daily:TaskBox:'.$user_id, 0, 2);
        }

    }
    public static function daily(User $user){
        $box = new static($user);
        $today_task_list = $box->today_list;
        return collect($today_task_list)->map(function($task) use ($user){
            return $task::create($user,rand(1,3));
        });
    }

}