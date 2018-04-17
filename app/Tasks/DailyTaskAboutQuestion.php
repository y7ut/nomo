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

class DailyTaskAboutQuestion extends DailyTask
{

    /**
     * @param User $user
     * @param $progress_size
     * @return DailyTask
     */
    public static function create(User $user, $progress_size)
    {
        $question_task=new static();
        $question_task->user=$user;
        if($question_task->exists()){
            $question_task=static::find($user->id);
            return $question_task;
        }
        $second = \Carbon\Carbon::tomorrow()->timestamp-\Carbon\Carbon::now()->timestamp;
        $question_task->progress_size=$progress_size;
        $question_task->task_content='提出'.$question_task->progress_size.'个问题。';
        $question_task->redis->hsetnx('Nomo:Task:Daily_question:'.$question_task->user->id,'task_user_id',$question_task->user->id);
        $question_task->redis->hsetnx('Nomo:Task:Daily_question:'.$question_task->user->id,'task_content',$question_task->task_content);
        $question_task->redis->hsetnx('Nomo:Task:Daily_question:'.$question_task->user->id,'task_progress_size',$question_task->progress_size);
        $question_task->redis->hsetnx('Nomo:Task:Daily_question:'.$question_task->user->id,'task_progress_number',$question_task->progress_number);
        $question_task->redis->hsetnx('Nomo:Task:Daily_question:'.$question_task->user->id,'task_state',$question_task->state);
        $question_task->redis->expire('Nomo:Task:Daily_question:'.$question_task->user->id, $second);
        return $question_task;
    }

    /**
     * @param $id
     *
     * @return DailyTask
     */
    public static function find($id){
        $question_task=new static();
        $question_task->user=User::find($id);
        if(!$question_task->exists()){
            return null;
        }
        $question_task->task_content=$question_task->redis->hgetall('Nomo:Task:Daily_question:'.$id)['task_content'];
        $question_task->progress_size=$question_task->redis->hgetall('Nomo:Task:Daily_question:'.$id)['task_progress_size'];
        $question_task->progress_number=$question_task->redis->hgetall('Nomo:Task:Daily_question:'.$id)['task_progress_number'];
        $question_task->state=$question_task->redis->hgetall('Nomo:Task:Daily_question:'.$id)['task_state'];
        return $question_task;
    }
    public function exists(){
        // TODO: Implement updateProgress() method.
        return $this->redis->hexists('Nomo:Task:Daily_question:'.$this->user->id,'task_user_id');
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
            $this->redis->hincrby('Nomo:Task:Daily_question:'.$this->user->id, 'task_progress_number', 1);
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
        $this->redis->hset('Nomo:Task:Daily_question:'.$this->user->id, 'task_state', 1);
    }
}