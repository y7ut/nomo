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

class DailyTaskCommentAttention extends DailyTask
{

    /**
     * @param User $user
     * @param $progress_size
     * @return DailyTask
     */
    public static function create(User $user, $progress_size)
    {
        $attention_task=new static();
        $attention_task->user=$user;
        if($attention_task->exists()){
            $attention_task=static::find($user->id);
            return $attention_task;
        }
        $second = \Carbon\Carbon::tomorrow()->timestamp-\Carbon\Carbon::now()->timestamp;
        $attention_task->progress_size=$progress_size;
        $attention_task->task_content='关注'.$attention_task->progress_size.'个文章。';
        $attention_task->redis->hsetnx('Nomo:Task:Daily_attention:'.$attention_task->user->id,'task_user_id',$attention_task->user->id);
        $attention_task->redis->hsetnx('Nomo:Task:Daily_attention:'.$attention_task->user->id,'task_content',$attention_task->task_content);
        $attention_task->redis->hsetnx('Nomo:Task:Daily_attention:'.$attention_task->user->id,'task_progress_size',$attention_task->progress_size);
        $attention_task->redis->hsetnx('Nomo:Task:Daily_attention:'.$attention_task->user->id,'task_progress_number',$attention_task->progress_number);
        $attention_task->redis->hsetnx('Nomo:Task:Daily_attention:'.$attention_task->user->id,'task_state',$attention_task->state);
        $attention_task->redis->expire('Nomo:Task:Daily_attention:'.$attention_task->user->id, $second);
        return $attention_task;
    }

    /**
     * @param $id
     *
     * @return DailyTask
     */
    public static function find($id){
        $attention_task=new static();
        $attention_task->user=User::find($id);
        if(!$attention_task->exists()){
            return null;
        }
        $attention_task->task_content=$attention_task->redis->hgetall('Nomo:Task:Daily_attention:'.$id)['task_content'];
        $attention_task->progress_size=$attention_task->redis->hgetall('Nomo:Task:Daily_attention:'.$id)['task_progress_size'];
        $attention_task->progress_number=$attention_task->redis->hgetall('Nomo:Task:Daily_attention:'.$id)['task_progress_number'];
        $attention_task->state=$attention_task->redis->hgetall('Nomo:Task:Daily_attention:'.$id)['task_state'];
        return $attention_task;
    }
    public function exists(){
        // TODO: Implement updateProgress() method.
        return $this->redis->hexists('Nomo:Task:Daily_attention:'.$this->user->id,'task_user_id');
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
            $this->redis->hincrby('Nomo:Task:Daily_attention:'.$this->user->id, 'task_progress_number', 1);
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
        $this->redis->hset('Nomo:Task:Daily_attention:'.$this->user->id, 'task_state', 1);
    }
}