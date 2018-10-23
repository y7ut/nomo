<?php

namespace App\Http\Controllers;

use App\Notify;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Matcher\Not;

class NotifiesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userid)
    {


            $message = User::find($userid)->notifies()->show()->orderBy('created_at','desc')->get();
            $notify = collect($message)->map(function($item){
                $post = Post::find($item->post_id);
                if(is_null($post)){
                    return [
                        'id'=>$item->id,
                        'content'=>$item->content,
                        'url'=>'#',
                        'read'=>(bool)$item->read_status,
                        'created_at'=>$item->created_at
                    ];
                }
                return [
                    'id'=>$item->id,
                    'content'=>$item->content,
                    'url'=>'/post/'.$post->createdAt().'/'.$post->url,
                    'read'=>(bool)$item->read_status,
                    'created_at'=>$item->created_at
                ];
            });
            return $notify;



    }


    public function read(Notify $notify)
    {
        //
        if($notify&&$notify->read_status==0){
            $notify->increment('read_status',1);
            return ['code'=>'200','result'=>'success'];
        }
        else{
            return ['code'=>'400','result'=>'fail'];
        }

    }

    public function readall($userid)
    {
        //
        $message = User::find($userid)->notifies()->get();
        collect($message)->map(function($item){
            $notify = Notify::find($item->id);
            $notify->read_status = 1;
            $notify->save();
        });
        return ['code'=>'200','result'=>'success'];

    }


}
