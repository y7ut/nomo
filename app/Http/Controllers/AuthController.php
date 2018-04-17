<?php

namespace App\Http\Controllers;

use App\Board;
use App\Http\Requests\EditBoardRequest;
use App\Http\Requests\SystemNotifyRequest;
use App\Notify;
use App\Permission;
use App\Post;
use App\Role;
use App\Tag;
use App\Tasks\EditPostAdminTask;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //
    public function authBoard(){
        if(Gate::denies('Admin')){
            return Redirect::to('/');
        }
        $boards = Board::all();
        $boards->first()->boardGod();
        $tags = Tag::all();
        $system = Db::table('system')->where('id', 1)->first();
        return view('authboard',compact('boards','tags','system'));
    }
    public function authUsers(){
        if(Gate::allows('Admin')){
            $users = User::paginate(4);
        }else{
            if(!Auth::user()->roleBoardId()){
                return Redirect::to('/');
            }
            $board =Board::find(Auth::user()->roleBoardId());
            $users = $board->attentionUser()->paginate(4);
        }
        return view('authuser',compact('users'));
    }
    public function authPosts(){

        if(Gate::allows('Admin')){
            $posts = Post::paginate(4);
        }else{
            if(!Auth::user()->roleBoardId()){
                return Redirect::to('/');
            }
            $board =Board::find(Auth::user()->roleBoardId());
            $posts = $board->posts()->paginate(4);
        }
        return view('authpost',compact('posts'));
    }
    public function authNotify(SystemNotifyRequest $request){
        if(Gate::allows('Admin')){
            $users = User::all();
        }else{

            $board =Board::find(Auth::user()->roleBoardId());
            $users = $board->attentionUser()->get();
        }
        try{
            $users->map(function($user) use ($request){
                $notify =new Notify();
                $notify->content = $request->input('content') ;
                $notify->user_id = $user->id;
                $notify->post_id = 0;
                $notify->read_status  = 0;
                $notify->type  = 0;
                $notify->save();
            });
        }catch(QueryException $exception){
            flash('创建失败')->error()->important();
            return Redirect::back();
        }
        flash('创建成功')->success()->important();
        return Redirect::back();
    }
    public function editBoard($id){
        $board = Board::find($id);

        return view('authboardedit',compact('board'));
    }

    public function randpic(){
        $user = Auth::user();
        $identicon =  new \Identicon\Identicon();
        $avatar=$identicon->getImageData(rand(1000,2000).rand(2000,9999).$user->name);
        $avatarname = $user->name.str_random(6).'.png';
        Storage::disk('public')->put($avatarname, $avatar);
        $user->update([
            'avatar'=>'/storage/'.$avatarname
        ]);
        if ($user->integration < 10) {
            flash('积分不足')->warning()->important();
            return Redirect::back();
        }else {
            $user->decrement('integration', 10);
            $returnnotify = new Notify();
            $returnnotify->content = '换了一换。积分-10';
            $returnnotify->user_id = $user->id;
            $returnnotify->post_id = 0;
            $returnnotify->read_status = 0;
            $returnnotify->type = 1;
            $returnnotify->save();
            flash('满意了么<-_<-')->success()->important();
            return Redirect::back();
        }
    }

    public function updateBoard(EditBoardRequest $request,$id){
        $board = Board::find($id);
        if($request->file('banner')){
            $file = $request->file('banner');
            $destinationPath = 'storage/boardimage';
            $filename = \Auth::user()->id.'_'.time().$file->getClientOriginalName();
            $file->move($destinationPath,$filename);
            $board->banner = '/'.$destinationPath.'/'.$filename;
        }
        $board->name=$request->input('name');
        $board->url=$request->input('url');
        $board->intro=$request->input('intro');
        $board->save();
        $new_user = User::find($request->input('user'));
        $board_user = $board->boardGod();

        if($board_user->id!=$new_user->id){
            $this_board_user_role = $board_user->roles->filter(function($item){
                return $item->name!='god';
            })->pluck('id')->flatten()->toArray();
            $the_role_new_user_has = $new_user->roles->filter(function($item){
                return $item->name!='god';
            })->pluck('id')->flatten()->toArray();;
            $this_board_role = $board->boardRole();

            if(!!!empty($this_board_user_role)){
                //如果这个版块的权限所有者他不是通过god管理的，则移除那条权限，如果是通过god,则跳过。
                $board_user->roles()->detach($this_board_role->id);
            }
            //删除新的用户的旧权限，用户只允许有一个权限
            $new_user->roles()->detach($the_role_new_user_has);
            //然后给予新的权限
            $new_user->roles()->attach($this_board_role->id);
        }
        flash('修改成功')->success()->important();
        return Redirect::to('setting/board');

    }
    public function storeBoard(EditBoardRequest $request){

        try{
            $board = new Board();
            $file = $request->file('banner');
            $destinationPath = 'storage/boardimage';
            $filename = \Auth::user()->id.'_'.time().$file->getClientOriginalName();
            $file->move($destinationPath,$filename);
            $board->banner = '/'.$destinationPath.'/'.$filename;
            $board->name=$request->input('name');
            $board->url=$request->input('url');
            $board->intro=$request->input('intro');
            $board->listnumber=Board::all()->count()+1;
            $board->save();
            $permission = new Permission();
            $permission->name=$board->id;
            $permission->label=$board->name.'管理';
            $permission->save();
            $role = new Role();
            $role->name =$board->id.'-boardgod';
            $role->label = $board->name.'-专栏管理';
            $role->save();
            $role->permissions()->attach($permission->id);
        }catch(QueryException $exception){
            flash('创建失败')->error()->important();
            return Redirect::back();
        }
        flash('创建成功')->success()->important();
        return Redirect::back();


    }
    public function destroyTag($id)
    {
        if(Gate::denies('Admin')){
            return Redirect::to('/');
        }
        try {

            $tag = Tag::find($id);
            $tag->delete();
        } catch (QueryException $exception) {
            flash('创建失败')->error()->important();
            return Redirect::back();
        }
        flash('删除成功')->success()->important();
        return Redirect::to('setting/board');
    }
    public function destroyBoard($id)
    {
        if(Gate::denies('Admin')){
            return Redirect::to('/');
        }
        try {
            $board = Board::find($id);
            $role = Role::where('name', $board->id . '-boardgod')->first();
            $permission = Permission::where('name', $board->id)->first();
            $board->delete();
            $role->delete();
            $permission->delete();
        } catch (QueryException $exception) {
            flash('创建失败')->error()->important();
            return Redirect::back();
        }
        flash('删除成功')->success()->important();
        return Redirect::to('setting/board');
    }
    public function banUsers(Request $request,$id){

        if(Gate::allows('Admin')){
            $users = User::all();
        }else{

            $board =Board::find(Auth::user()->roleBoardId());
            $users = $board->attentionUser()->get();
        }
        $this->validate($request, [
            'datetime'=>'required',
        ]);
        $user = User::find($id);
        if($users->contains($user)){
           $date = $user->banUser($request->input('datetime'));
            flash('成功封禁：'.$date)->success()->important();
            return Redirect::to('setting/user');
        }
        else{
            flash('出错了')->danger()->important();
            return Redirect::to('setting/user');
        }
    }
    public function outBan($id){
        if(Gate::allows('Admin')){
            $users = User::all();
        }else{

            $board =Board::find(Auth::user()->roleBoardId());
            $users = $board->attentionUser()->get();
        }
        $user = User::find($id);
        if($users->contains($user)){
            $date = $user->delBan();
            flash('解除成功')->success()->important();
            return Redirect::to('setting/user');
        }
        else{
            flash('出错了')->danger()->important();
            return Redirect::to('setting/user');
        }
    }
    public function authPostsEdit($id){
        if(Gate::allows('Admin')){
            $posts = Post::all();
        }else{
            if(!Auth::user()->roleBoardId()){
                return Redirect::to('/');
            }
            $board =Board::find(Auth::user()->roleBoardId());

            $posts = $board->posts()->get();
        }
        $post = Post::find($id);
        if($posts->contains($post)){
            if($post->isNeedEdit()){
                flash('整改中..已通知')->error()->important();
                return Redirect::to('setting/post');
            }
            EditPostAdminTask::create($post);
            flash('已通知用户整改，三天后未修改则删除')->success()->important();
            return Redirect::to('setting/post');
        }
        else{
            flash('出错了')->danger()->important();
            return Redirect::to('setting/post');
        }
    }
    public function systemSetting(Request $request){
        $this->validate($request, [
            'content'=>'required|max:40',
        ]);
        $title = $request->input('content');
        if($request->file('banner')){
            $file = $request->file('banner');
            $destinationPath = 'storage/';
            $filename = \Auth::user()->id.'_'.time().$file->getClientOriginalName();
            $file->move($destinationPath,$filename);
            $banner = '/'.$destinationPath.'/'.$filename;
            Db::table('system')->where('id', 1)->update(['system_banner'=>$banner,'system_title'=>$title]);
        }
        Db::table('system')->where('id', 1)->update(['system_title'=>$title]);
        flash('修改成功')->success()->important();
        return Redirect::to('setting/board');
    }
}
