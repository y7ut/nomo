<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/7
 * Time: 13:55
 */

namespace App\Tasks;


use App\Post;
use App\User;

class EditPostAdminTask extends AdminTask
{
    protected $post;

    /**
     * @param User $user
     * @param $progress_size
     * @return DailyTask
     */
    public static function create(User $user, Post $post)
    {
        $post_task=new static();
        $post_task->user=$user;
        $post_task->post=$post;
        if($post_task->exists()){
            $post_task=static::find($user->id);
            return $post_task;
        }
        $second = \Carbon\Carbon::tomorrow()->timestamp-\Carbon\Carbon::now()->timestamp;
        $post_task->redis->hsetnx('Nomo:Task:Admin_post:'.$post_task->user->id.':'.$post_task->post->id,'task_user_id',$post_task->user->id);
        $post_task->redis->hsetnx('Nomo:Task:Admin_post:'.$post_task->user->id.':'.$post_task->post->id,'task_post_id',$post_task->post->id);
        $post_task->redis->hsetnx('Nomo:Task:Admin_post:'.$post_task->user->id.':'.$post_task->post->id,'task_state',$post_task->state);
        $post_task->redis->hsetnx('Nomo:Task:Admin_post:'.$post_task->user->id.':'.$post_task->post->id,'task_content',$post_task->task_content);
        $post_task->redis->expire('Nomo:Task:Admin_post:'.$post_task->user->id, $second);
        return $post_task;
    }

    public function updateStates()
    {
        // TODO: Implement updateStates() method.
    }

    public function exists()
    {
        // TODO: Implement exists() method.
        return $this->redis->hexists('Nomo:Task:Admin_post:'.$this->user->id.':'.$this->post->id,'task_user_id');
    }
}