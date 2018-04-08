<?php
/**
 * Created by PhpStorm.
 * User: XYX
 * Date: 2018/3/17
 * Time: 20:51
 */

namespace App\Observers;


use App\Notify;
use App\Order;

class OrderObservers
{
    /**
     * 监听用户创建的事件。
     *
     * @param  Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
            $notify =new Notify();
            $notify->content = $order->buyUser->name.'购买了你的文章《'.$order->post->title.'》。积分+'.$order->charge_integration;
            $notify->user_id = $order->ownerUser->id;
            $notify->post_id = $order->post_id;
            $notify->read_status  = 0;
            $notify->type  = 0;
            $notify->save();

            $notifyother =new Notify();
            $notifyother->content = '你购买了'.$order->ownerUser->name.'的文章《'.$order->post->title.'》。积分-'.$order->charge_integration;
            $notifyother->user_id = $order->buyUser->id;
            $notifyother->post_id = $order->post_id;
            $notifyother->read_status  = 0;
            $notifyother->type  = 0;
            $notifyother->save();

    }
}