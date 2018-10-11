<?php

namespace App\Http\Controllers;

use App\Events\Attention;
use App\Http\Requests\NewPostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Notify;
use App\Post;
use App\Tag;
use App\Tasks\DailyTaskAboutAttention;
use App\Tasks\DailyTaskCommentAttention;
use App\User;
use Carbon\Carbon;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(!Auth::check()){
            flash('请先登录')->warning()->important();
            return redirect('/');
        }
        if(Auth::user()->isBan()){
            flash('您的账号已被禁言至'.Auth::user()->isBan().'请您您耐心等待')->warning()->important();
            return redirect('/');
        }
        return view('postedit');
    }

    public function search(Request $request){
        $this->validate($request, [
            'content'=>'required|max:40',
        ]);
        $search = $request->input('content');
        $posts = Post::where('title', 'like', '%'.$search.'%')
            ->paginate(10);
        $title =  '”'.$search.'“的搜索结果';
        $smtitle =  '共'.$posts->count().'项';
        $display = 1;
        return view('postlist',compact('posts','title','smtitle','display'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewPostRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewPostRequest $request)
    {
        //
//        dd($request->all());
        if($request->input('needintergation')=='on'
            &&$request->input('intergation')>(Auth::user()->integration/10)){
            flash('不要太贪心哦，设置积分不得超过'.Auth::user()->integration/10)->warning()->important();
            return Redirect::back();
        }
        $post = new Post();
        $post->title = $request->input('title');
        $post->content =$request->input('content');
        $post->url = $request->input('name');
        $post->user_id =Auth::id();
        $post->board_id =$request->input('board');
        $post->posttype =$request->input('type');
        $post->url =pinyin_abbr($request->input('title'));
        if($request->input('needintergation')=='on'){
            $post->charge ='1';
            $post->integration_charge =$request->input('intergation');
        }
        if($request->file('background')){
            $file = $request->file('background');
            $destinationPath = 'storage/postimage';
            $filename = \Auth::user()->id.'_'.time().$file->getClientOriginalName();
            $file->move($destinationPath,$filename);
            $post->background = '/'.$destinationPath.'/'.$filename;
        }else{
            $box=['1','5','6','c','d','7','0','2','3','4','a','b','8','9','e','f'];
            $color=[];
            for($i=1;$i<=6;$i++){
                $color[$i]=array_random($box);
            }
            $color = '#'.implode($color);
            $post->background = $color;
        }
        $tags = collect($request->input('tag'))->map(function($tag){
            if(is_numeric($tag)){
                return $tag;
            }
            return Tag::firstOrCreate(['name'=>$tag],['created_id'=>Auth::id()])->id;
        })->toArray();
        $post->save();
        $post->tags()->attach($tags);
        flash('发布成功')->success()->important();
        return Redirect::to('post/'.$post->createdAt().'/'.$post->url);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($datetime,$url)
    {

        $datetime = substr_replace(substr_replace($datetime,'-',4,0),'-',7,0);
        $post = Post::where('url',$url)->whereDate('created_at', $datetime)->first();
        $title = $post->title;
        if($post->isCharge()){
            if(Auth::check()){
                return view('post',compact('post','title'));
            }
            flash('登陆后才可查看优选内容哦')->warning()->important();
            return redirect('/');
        }
        else{
            if(!Auth::check()){
                flash('登陆后浏览更方便哦')->success()->important();
            }
            return view('post',compact('post','title'));
        }


    }
    public function newColor($datetime, $url, $token){
        if (isset($token)) {
            $tokenstr  = Crypt::decrypt($token);//解密Token
            $user = User::find(substr($tokenstr, 10));
            $post      = Post::where('url', $url)
                ->whereDate('created_at', $datetime)
                ->first();
            if($user->can('editPost',$post)){
                $box=['1','5','6','c','d','7','0','2','3','4','a','b','8','9','e','f'];
                $color=[];
                for($i=1;$i<=6;$i++){
                    $color[$i]=array_random($box);
                }
                $color = '#'.implode($color);
                try{

                    $post->background=$color;
                    $post->save();
                }catch(QueryException $exception){
                    flash('出错啦')->error()->important();
                    return Redirect::back();
                }
                flash('更换成功')->info()->important();
                return Redirect::back();

            }
        }
        flash('出了点小问题')->error()->important();
        return Redirect::back();
    }


    /**
     * 关注文章
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function attentionPost($datetime, $url, $token)
    {
        //
        if (isset($token)) {
            $tokenstr  = Crypt::decrypt($token);//解密Token
            $user = User::find(substr($tokenstr, 10));
            $post      = Post::where('url', $url)
                ->whereDate('created_at', $datetime)
                ->first();

            try{
                $user->setPostAttention($post);
                $post->increment('attention_count',1);
                event(new Attention($user,$post));
                $task=DailyTaskCommentAttention::find($user->id);
                if(isset($task)){
                    $task->updateProgress();
                }
            }catch(QueryException $exception){
                flash('不能重复订阅')->error()->important();
                return Redirect::back();
            }
            flash('订阅成功')->info()->important();
            return Redirect::back();

        }
        flash('出了点小问题')->error()->important();
        return Redirect::back();


    }
    /**
     * 取消关注文章
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function offPost($datetime, $url, $token)
    {
        //
        if (isset($token)) {
            $tokenstr  = Crypt::decrypt($token);//解密Token
            $user = User::find(substr($tokenstr, 10));
            $post      = Post::where('url', $url)
                ->whereDate('created_at', $datetime)
                ->first();

            try{
                $user->deletePostAttention($post);
                $post->decrement('attention_count',1);
            }catch(QueryException $exception){
                flash('不能重复取消')->error()->important();
                return Redirect::back();
            }
            flash('取消成功')->info()->important();
            return Redirect::back();

        }
        flash('出了点小问题')->error()->important();
        return Redirect::back();


    }

    /**
     * 查看订阅的文章
     *
     * @return \Illuminate\Http\Response
     */
    public function attentionShow()
    {
        $title =  Auth::user()->name.'的订阅列表';

        if(Auth::user()->attentionPost()->get()->count()){
            $smtitle =  '您共订阅了'.Auth::user()->attentionPost()->count().'个内容。';
        }else{
            $smtitle =  '什么都没有，快去翻翻吧';
        }

        $display = 1;
        $posts = Auth::user()->attentionPost()->paginate(10);

        return view('postlist',compact('posts','title','smtitle','display'));
    }
    /**
     * 关闭评论
     *
     * @param  $datetime
     * @param  $url
     * @param  $token
     * @return \Illuminate\Http\Response
     */
    public function closeComment($datetime, $url, $token)
    {
        //
        if (isset($token)) {
            $tokenstr  = Crypt::decrypt($token);//解密Token
            $user = User::find(substr($tokenstr, 10));
            $post      = Post::where('url', $url)
                ->whereDate('created_at', $datetime)
                ->first();
            if($user->can('editPost',$post)){

                try{
                    $post->discomment=1;
                    $post->save();
                }catch(QueryException $exception){
                    flash('不能重复关闭')->error()->important();
                    return Redirect::back();
                }
                flash('关闭成功')->info()->important();
                return Redirect::back();

            }
        }
        flash('出了点小问题')->error()->important();
        return Redirect::back();

    }

    /**
     * 打开评论
     *
     * @param  $datetime
     * @param  $url
     * @param  $token
     * @return \Illuminate\Http\Response
     */
    public function openComment($datetime, $url, $token)
    {
        //
        if (isset($token)) {
            $tokenstr  = Crypt::decrypt($token);//解密Token
            $user = User::find(substr($tokenstr, 10));
            $post      = Post::where('url', $url)
                ->whereDate('created_at', $datetime)
                ->first();
            if($user->can('editPost',$post)){

                try{
                    $post->discomment=0;
                    $post->save();
                }catch(QueryException $exception){
                    flash('不能重复开启')->error()->important();
                    return Redirect::back();
                }
                flash('开启成功')->info()->important();
                return Redirect::back();

            }

        }
        flash('出了点小问题')->error()->important();
        return Redirect::back();

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($datetime, $url, $token)
    {
        if (isset($token)) {
            $tokenstr  = Crypt::decrypt($token);//解密Token
            $user = User::find(substr($tokenstr, 10));
            $post      = Post::where('url', $url)
                ->whereDate('created_at', $datetime)
                ->first();
            if($user->can('editPost',$post)){
                return view('editpost',compact('post'));
            }
        }
        flash('出了点小问题')->error()->important();
        return Redirect::back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $rules = ['captcha' => 'required|captcha'];
        $validator = Validator::make(Input::all(), $rules);
        if($validator->fails()){
            flash('验证码错误')->error()->important();
            return Redirect::back();
        }
        else{
            Post::destroy(Input::get('id'));
            flash('删除成功，可在设置中使用积分恢复哦')->success()->important();
            return redirect('/');
        }
    }
    public function delPostReturn($id){
            $user = Auth::user();
            $post      = Post::withTrashed()
                ->find($id);
            if($user->can('editPost',$post)){
                $userint   =$user->integration;
                if ($userint < 100) {
                    flash('积分不足')->warning()->important();
                    return Redirect::back();
                }else{
                    $user->decrement('integration',100);
                    $post->restore();
                    $returnnotify =new Notify();
                    $returnnotify->content = '你恢复了文章《'.$post->title.'》。积分-100';
                    $returnnotify->user_id = $user->id;
                    $returnnotify->post_id = 0;
                    $returnnotify->read_status  = 0;
                    $returnnotify->type  = 1;
                    $returnnotify->save();
                    flash('成功恢复！积分-100')->info()->important();
                    return Redirect::back();
                }
            }
        flash('出了点小问题')->error()->important();
        return Redirect::back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePostRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        //
        $post = Post::find($id);
        if(Auth::user()->cannot('editPost',$post)){
            flash('出错了')->error()->important();
            return Redirect::back();
        }


        $input = collect($request->input('tag'))->map(function($tag){
            if(is_numeric($tag)){
                return $tag;
            }
            return ''.Tag::firstOrCreate(['name'=>$tag],['created_id'=>Auth::id()])->id;
        })->toArray();

        $now = collect($post->tags->pluck('id'))->toArray();

        $post->tags()->attach(collect($input)->diff($now));
        $post->tags()->detach(collect($now)->diff($input));
        $content = [
            'content'=>$request->input('content')
        ];
        $post->update($content);
        flash('修改成功')->success()->important();
        return Redirect::to('/post/'.$post->createdAt().'/'.$post->url);


    }
    public function personalPost($token){
        $tokenstr  = Crypt::decrypt($token);//解密Token
        $user = User::find(substr($tokenstr, 10));

        $posts = Post::where('user_id',$user->id)->paginate(10);
        $title =  $user->name.'的内容。';
        $postdelete = Post::onlyTrashed()->where('user_id','=',Auth::id())->get();
        $smtitle =  '';
        $display  = 1;

        return view('postlist',compact('posts','title','smtitle','display','postdelete'));
    }
}
