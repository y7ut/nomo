<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2017/11/29
 * Time: 19:36
 */

namespace App\Polices;


use App\Post;
use Illuminate\Support\Facades\Auth;

class PostPolices
{
    /**
     * 判断指定博客是否收费可以观看。
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function showCharge($user,$post)
    {

        if($post->user==$user){
            return 1;
        }
            return $post->buyorders->contains($user);

    }
    /**
     * 判断指定博客是否可以被关注。
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function sureAttention($user,$post)
    {

        if($post->user==$user){
            return 0;
        }
        return !!!$post->attentionUser->contains($user);

    }

    /**
     * 判断指定博客可否被修改。
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function editPost($user,$post)
    {

        return $post->user==$user;

    }
    /**
     * 判断指定博客可否被删除。
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function deletePost($user,$post)
    {

        return $post->user==$user;

    }

}