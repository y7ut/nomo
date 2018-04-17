<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/3/17
 * Time: 22:41
 */

namespace App\Observers;


use App\Events\TaskUpdate;
use App\Notify;
use App\Post;
use App\Tasks\DailyTaskAboutPost;
use App\Tasks\DailyTaskAboutQuestion;
use App\Tasks\EditPostAdminTask;
use Illuminate\Support\Facades\Auth;

class PostObservers
{
    /**
     * 监听用户更新的事件。通知关注者
     *
     * @param  Post $post
     * @return void
     */
    public function updated(Post $post)
    {
        //
        if($post->isNeedEdit()){
            $task = EditPostAdminTask::find($post->id);
            $task->updateProgress();
        }
        $attention = $post->attentionUser;
        $attention->map(function($user) use ($post){
            $notify =new Notify();
            $notify->content = '你关注的文章《'.$post->title.'》更新了。';
            $notify->user_id = $user->id;
            $notify->post_id = $post->id;
            $notify->read_status  = 0;
            $notify->type  = 0;
            $notify->save();
        });
    }
    public function created(Post $post){
        if($post->posttype==1){
            $task=DailyTaskAboutQuestion::find($post->user->id);
        }elseif($post->posttype==0){

            $task=DailyTaskAboutPost::find($post->user->id);
        }
        if(isset($task)){
            $task->updateProgress();
        }
        $board = $post->board;
        $board->attentionUser->filter(function($user){
            return $user->id!= Auth::id();
        })->map(function($user) use ($post){
            $notify =new Notify();
            $notify->content = $post->board->name.'专栏有新文章《'.$post->title.'》发布了。';
            $notify->user_id = $user->id;
            $notify->post_id = $post->id;
            $notify->read_status  = 0;
            $notify->type  = 0;
            $notify->save();
        });

    }
}