<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/4/7
 * Time: 13:55
 */

namespace App\Tasks;


use App\Events\TaskUpdate;
use App\Jobs\NotAllowPost;
use App\Notify;
use App\Post;
use App\User;

class EditPostAdminTask extends AdminTask
{
    protected $post;

    /**
     * @param Post $post
     * @return DailyTask
     * @internal param User $user
     * @internal param $progress_size
     */
    public static function create(Post $post)
    {
        $post_task=new static();
        $post_task->user=$post->user;
        $post_task->post=$post;
        if($post_task->exists()){
            $post_task=static::find($post->id);
            return $post_task;
        }
        $post_task->task_content = "你好，".$post_task->user->name."你的文章“".$post_task->post->title."”内容违规请在三天内修改，已加入枪毙名单。";
        $second = \Carbon\Carbon::now()->addDays(3)->timestamp-\Carbon\Carbon::now()->timestamp;
        $post_task->redis->setnx('Nomo:Task:Admin_post:'.$post_task->user->id.':'.$post_task->post->id,$post_task->task_content);
        $post_task->redis->expire('Nomo:Task:Admin_post:'.$post_task->user->id.':'.$post_task->post->id, $second);
        $notify =new Notify();
        $notify->content = $post_task->task_content();
        $notify->user_id = $post_task->user->id;
        $notify->post_id = $post_task->post->id;
        $notify->read_status  = 0;
        $notify->type  = 0;
        $notify->save();
        return $post_task;
    }
    /**
     * @param $id
     *
     * @return DailyTask
     */
    public static function find($id){
        $post_task=new static();
        $post = Post::find($id);
        if(!!!$post){
            return null;
        }
        $post_task->post=Post::find($id);
        $post_task->user= $post_task->post->user;
        if(!!!$post_task->exists()){
            return null;
        }
        $post_task->task_content=$post_task->redis->get('Nomo:Task:Admin_post:'.$post_task->user->id.':'.$post_task->post->id);
        return $post_task;
    }
    public function updateProgress()
    {
        // TODO: Implement updateStates() method.
        //只做一次

            event(new TaskUpdate($this));
            return $this->redis->del('Nomo:Task:Admin_post:'.$this->user->id.':'.$this->post->id);

    }

    public function exists()
    {
        // TODO: Implement exists() method.
        return $this->redis->get('Nomo:Task:Admin_post:'.$this->user->id.':'.$this->post->id );
    }
    public function __call($method,$arg){
        return $this->$method;
    }
}