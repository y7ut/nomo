<?php

namespace App\Http\Controllers;

use App\Order;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    //
    /**
     * User Bu the post.
     * @param $datetime 时间 $url 文章标识 $token 授权码
     * @return \Illuminate\Http\Response
     */
    public function buyPost($datetime, $url, $token)
    {
        //无Token 非法请求
        if (!!!isset($token)) {
            flash('出了点小问题')->danger()->important();
            return Redirect::back();
        }
        $tokenstr  = Crypt::decrypt($token);//解密Token
        $buyuserid = substr($tokenstr, 10);
        $post      = Post::where('url', $url)
                     ->whereDate('created_at', $datetime)
                     ->first();
        $ownerid   = $post->user->id;
        //是否可以购买
        $userint   = Db::table('users')
                     ->where('id', $buyuserid)
                     ->first()
                     ->integration;
        if ($userint < $post->integration_charge) {
            flash('积分不足 <a href="/intergation">立即获取</a>')->warning()->important();
            return Redirect::back();
        }
        //执行事务
        DB::transaction(function () use ($ownerid, $buyuserid, $post) {
            $order = new Order();
            $order->post_id             = $post->id;
            $order->buy_user_id         = $buyuserid;
            $order->owner_user_id       = $ownerid;
            $order->charge_integration  = $post->integration_charge;
            $order->save();
            Db::table('users')
                ->where('id', $ownerid)
                ->increment('integration', $post->integration_charge);
            Db::table('users')
                ->where('id', $buyuserid)
                ->decrement('integration', $post->integration_charge);
        });
        flash('购买成功')->success()->important();
        return Redirect::back();

    }


}
