<?php

namespace App\Http\Controllers;

use App\Board;
use App\Events\BoardAttention;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BoardController extends Controller
{
    //
    public function index(){
        $boards = Board::all();
        return view('board',compact('boards'));
    }
    public function selectBoard(){
        $board = Board::all()->map(function($board){
            return [
                'id'=>$board->id,
                'text'=>$board->name
            ];
        })->toArray();

        return json_encode($board);
    }
    public function boardList($url){
        $board = Board::where('url',$url)->first();
        $title = $board->name;
        $smtitle = $board->intro;
        $posts = $board->posts()->paginate(10);
        return view('boardlist',compact('posts','title','smtitle','board'));
    }
    public function attentionBoard($url){
        if(!Auth::check()){
            flash('请先登录')->warning()->important();
            return Redirect::back();
        }
        $board = Board::where('url',$url)->first();
        $user = Auth::user();

        try{
            $user->BoardAttention($board);
            $board->increment('attended_count',1);
            event(new BoardAttention($user,$board));
        }catch(QueryException $exception){
            flash('不能重复订阅')->error()->important();
            return Redirect::back();
        }
        flash('订阅成功')->success()->important();
        return Redirect::back();

    }
    public function offBoard($url){
        if(!Auth::check()){
            flash('请先登录')->warning()->important();
            return Redirect::back();
        }
        $board = Board::where('url',$url)->first();
        $user = Auth::user();

        try{
            $user->deleteBoardAttention($board);
            $board->decrement('attended_count',1);
        }catch(QueryException $exception){
            flash('不能重复取消')->error()->important();
            return Redirect::back();
        }
        flash('取消成功')->success()->important();
        return Redirect::back();

    }
}
