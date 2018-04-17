<?php

namespace App\Http\Controllers;

use App\Board;
use App\Post;
use App\Tasks\TaskBox;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     *
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $system = Db::table('system')->where('id', 1)->first();
        $boards  =  Board::orderBy('listnumber')
                    ->with('posts')
                    ->get();
        $zeroPost = Post::where('comment_count',0)->where('discomment',0)
                    ->inRandomOrder()
                    ->take(5)
                    ->get();
        $hotuser = User::orderBy('integration','desc')->take(4)->get();
        $question = Post::where('comment_count',0)->where('posttype',1)
            ->inRandomOrder()
            ->first();
        if(is_null($question)){
            $question = Post::where('posttype',1)
                ->inRandomOrder()
                ->first();
        }
        if(Auth::user()){
            $tasks = TaskBox::daily(Auth::user());
            return view('home',compact('boards','zeroPost','hotuser','system','question','tasks'));
        }else{
            return view('home',compact('boards','zeroPost','hotuser','system','question'));
        }

    }
}
