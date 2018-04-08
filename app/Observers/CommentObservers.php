<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/3/17
 * Time: 20:02
 */

namespace App\Observers;
use App\Comment;
use App\Notify;
use App\Tasks\DailyTaskAboutComment;
use App\User;

class CommentObservers
{

    /**
     * 监听用户创建的事件。
     *
     * @param  User  $user
     * @return void
     */
    public function created(Comment $comment)
    {
        //
        $task=DailyTaskAboutComment::find($comment->user->id);
        if(isset($task)){
            $task->updateProgress();
        }
        if($comment->father_id=='0'){
            //评论文章
            if($comment->post->user->id == $comment->user->id){
                //评论自己文章不触发事件
                return 0;
            }
            User::find($comment->post->user->id)->increment('integration',2);
            User::find($comment->user->id)->increment('integration',1);
            $notify =new Notify();
            $notify->content = $comment->user->name.'回复了你的文章《'.$comment->post->title.'》。积分+2';
            $notify->user_id = $comment->post->user->id;
            $notify->post_id = $comment->post->id;
            $notify->read_status  = 0;
            $notify->type  = 0;
            $notify->save();
        }else{
            //回复评论
            if($comment->fatherComment->user->id == $comment->user->id){
                //父评论和评论者相同不触发时间
                return 0;
            }
            User::find($comment->user->id)->increment('integration',1);
            $notify =new Notify();
            $notify->content = $comment->user->name.'回复了你在《'.$comment->post->title.'》文章的评论。';
//            dd($comment->fatherComment);
            $notify->user_id = $comment->fatherComment->user->id;
            $notify->post_id = $comment->post->id;
            $notify->read_status  = 0;
            $notify->type  = 0;
            $notify->save();
        }

    }
}