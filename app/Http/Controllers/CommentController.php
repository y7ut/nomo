<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\NewCommentsRequest;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    //
    public function addComment(NewCommentsRequest $request,$id,$father_id=0){
        if(Auth::user()->isBan()){
            flash('您的账号已被禁言至'.Auth::user()->isBan().'请您您耐心等待')->warning()->important();
            return Redirect::back();
        }
        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->user_id = Auth::id();
        $comment->father_id = $father_id;
        $comment->post_id = $id;
        Post::find($id)->increment('comment_count',1);
        if($comment->save()){
            flash('评论成功')->success()->important();
            return Redirect::back();
        }


    }
}
