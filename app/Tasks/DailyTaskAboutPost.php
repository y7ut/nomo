<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/6
 * Time: 11:40
 */

namespace App\Tasks;


use App\Events\TaskFinish;
use App\User;

class DailyTaskAboutPost extends DailyTask
{

    /**
     * @param User $user
     * @param $progress_size
     * @return DailyTask
     */
    public static function create(User $user, $progress_size)
    {
        $post_task=new static();
        $post_task->user=$user;
        if($post_task->exists()){
            $post_task=static::find($user->id);
            return $post_task;
        }
        $second = \Carbon\Carbon::tomorrow()->timestamp-\Carbon\Carbon::now()->timestamp;
        $post_task->progress_size=$progress_size;
        $post_task->task_content='发表'.$post_task->progress_size.'个文章。';
        $post_task->redis->hsetnx('Nomo:Task:Daily_post:'.$post_task->user->id,'task_user_id',$post_task->user->id);
        $post_task->redis->hsetnx('Nomo:Task:Daily_post:'.$post_task->user->id,'task_content',$post_task->task_content);
        $post_task->redis->hsetnx('Nomo:Task:Daily_post:'.$post_task->user->id,'task_progress_size',$post_task->progress_size);
        $post_task->redis->hsetnx('Nomo:Task:Daily_post:'.$post_task->user->id,'task_progress_number',$post_task->progress_number);
        $post_task->redis->hsetnx('Nomo:Task:Daily_post:'.$post_task->user->id,'task_state',$post_task->state);
        $post_task->redis->expire('Nomo:Task:Daily_post:'.$post_task->user->id, $second);
        return $post_task;
    }

    /**
     * @param $id
     *
     * @return DailyTask
     */
    public static function find($id){
        $post_task=new static();
        $post_task->user=User::find($id);
        if(!$post_task->exists()){
            return null;
        }
        $post_task->task_content=$post_task->redis->hgetall('Nomo:Task:Daily_post:'.$id)['task_content'];
        $post_task->progress_size=$post_task->redis->hgetall('Nomo:Task:Daily_post:'.$id)['task_progress_size'];
        $post_task->progress_number=$post_task->redis->hgetall('Nomo:Task:Daily_post:'.$id)['task_progress_number'];
        $post_task->state=$post_task->redis->hgetall('Nomo:Task:Daily_post:'.$id)['task_state'];
        return $post_task;
    }
    public function exists(){
        // TODO: Implement updateProgress() method.
        return $this->redis->hexists('Nomo:Task:Daily_post:'.$this->user->id,'task_user_id');
    }
    public function getState(){

        return $this->state;
    }


    public function updateProgress()
    {
        // TODO: Implement updateProgress() method.

        if($this->progress_number<$this->progress_size){
            $this->progress_number++;
            if($this->progress_number==$this->progress_size){
                if($this->state!=1){
                    $this->updateState();
                }
                $this->state=1;

            }
            $this->redis->hincrby('Nomo:Task:Daily_post:'.$this->user->id, 'task_progress_number', 1);
        }
        return $this;
    }
    public function __call($method,$arg){
        return $this->$method;
    }

    public function updateState()
    {
        // TODO: Implement updateState() method.
        event(new TaskFinish($this,$this->user));
        $this->redis->hset('Nomo:Task:Daily_post:'.$this->user->id, 'task_state', 1);
    }
}