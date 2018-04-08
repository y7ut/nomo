<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Naux\Mail\SendCloudTemplate;

class EmailController extends Controller
{
    //
    public function verify($token){
        $user = User::where('confirmation_token',$token)->first();
        if(is_null($user)){
            flash('邮箱验证失败')->error()->important();
            return redirect('/');
        }
        $user->is_active = 1;
        $user->confirmation_token = str_random(40);
        $user->save();
        flash('邮箱验证成功')->success()->important();
        Auth::login($user);
        return redirect('/');
    }
    public function remail($token){
        $user = User::where('confirmation_token',$token)->first();
        $data = [
            'url'=>route('email.verify',['token'=>$user->confirmation_token]),
            'name'=>$user->name
        ];
        $template = new SendCloudTemplate('zhihu_app_register',$data);
        Mail::raw($template,function($message) use($user){
            $message->from('Nomo@gmail.com','Nomo');
            $message->to($user->email);
        });
        flash('已重新发送，请验证邮箱 ')->success()->important();
        return redirect('/login');
    }
}
