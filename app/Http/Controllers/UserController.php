<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    //
    public function signup(){

        $userid = Auth::id();
        $date = date('Y-m-d');
        $yesterdaydate = date('Y-m-d',strtotime("-1 day"));
        if(DB::table('sign')->where([['user_id','=',$userid],['date','=',$date]])->first()){
            flash('不能重复签到哦')->error()->important();
            return Redirect::back();
        }
        DB::transaction(function () use($userid,$date,$yesterdaydate) {

            DB::table('sign')->insert(['user_id' => $userid, 'date' => $date]);
            Db::table('users')->where('id', $userid)->increment('integration',10);
            if(DB::table('sign')->where([['user_id','=',$userid],['date','=',$yesterdaydate]])->first()){
                Db::table('users')->where('id', $userid)->increment('signin_count',1);
            }else{
                Db::table('users')->where('id', $userid)->update(['signin_count'=>'1']);
            }

        });
        $day = User::find($userid)->signin_count;
        flash('已签到'.$day.'天')->success()->important();
        return Redirect::back();

    }
    public function getSign(){

        dd(Auth::User());
        return Auth::id();
    }
    public function selectUser(){
        $user = User::all()->map(function($user){
            return [
                'id'=>$user->id,
                'text'=>$user->name
            ];
        })->toArray();

        return json_encode($user);
    }

}
