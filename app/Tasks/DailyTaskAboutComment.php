<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/4
 * Time: 20:39
 */

namespace App\Tasks;


use App\Events\TaskFinish;
use App\User;

class DailyTaskAboutComment extends DailyTask
{

    /**
     * @param User $user
     * @param $progress_size
     * @return DailyTask
     */
    public static function create(User $user, $progress_size)
    {
        $comment_task=new static();
        $comment_task->user=$user;
        if($comment_task->exists()){
            $comment_task=static::find($user->id);
            return $comment_task;
        }
        $second = \Carbon\Carbon::tomorrow()->timestamp-\Carbon\Carbon::now()->timestamp;
        $comment_task->progress_size=$progress_size;
        $comment_task->task_content='发表'.$comment_task->progress_size.'个评论。';
        $comment_task->redis->hsetnx('Nomo:Task:Daily_comment:'.$comment_task->user->id,'task_user_id',$comment_task->user->id);
        $comment_task->redis->hsetnx('Nomo:Task:Daily_comment:'.$comment_task->user->id,'task_content',$comment_task->task_content);
        $comment_task->redis->hsetnx('Nomo:Task:Daily_comment:'.$comment_task->user->id,'task_progress_size',$comment_task->progress_size);
        $comment_task->redis->hsetnx('Nomo:Task:Daily_comment:'.$comment_task->user->id,'task_progress_number',$comment_task->progress_number);
        $comment_task->redis->hsetnx('Nomo:Task:Daily_comment:'.$comment_task->user->id,'task_state',$comment_task->state);
        $comment_task->redis->expire('Nomo:Task:Daily_comment:'.$comment_task->user->id, $second);
        return $comment_task;
    }

    /**
     * @param $id
     *
     * @return DailyTask
     */
    public static function find($id){
        $comment_task=new static();
        $comment_task->user=User::find($id);
        if(!$comment_task->exists()){
            return null;
        }
        $comment_task->task_content=$comment_task->redis->hgetall('Nomo:Task:Daily_comment:'.$id)['task_content'];
        $comment_task->progress_size=$comment_task->redis->hgetall('Nomo:Task:Daily_comment:'.$id)['task_progress_size'];
        $comment_task->progress_number=$comment_task->redis->hgetall('Nomo:Task:Daily_comment:'.$id)['task_progress_number'];
        $comment_task->state=$comment_task->redis->hgetall('Nomo:Task:Daily_comment:'.$id)['task_state'];
        return $comment_task;
    }
    public function exists(){
        // TODO: Implement updateProgress() method.
        return $this->redis->hexists('Nomo:Task:Daily_comment:'.$this->user->id,'task_user_id');
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
            $this->redis->hincrby('Nomo:Task:Daily_comment:'.$this->user->id, 'task_progress_number', 1);
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
        $this->redis->hset('Nomo:Task:Daily_comment:'.$this->user->id, 'task_state', 1);
    }
}