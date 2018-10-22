<?php

namespace App;

use App\Events\Attention;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\HasApiTokens;
use Naux\Mail\SendCloudTemplate;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','intro','url','confirmation_token','comment_tail','integration','posts_count','comments_count','attendboard_count','follower_count','followed_count','sign_count','lastsign','is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

//    public function setPasswordAttribute($password)
//    {
//        if (Hash::needsRehash($password)) {
//            $this->attributes['password'] = Hash::make($password);
//        }
//
//    }
    /**
     * 获取用户创建的时间，默认100天前输出完整时间，否则输出人性化的时间。
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {

        if (Carbon::now() > Carbon::parse($value)->addDays(100)) {
            return Carbon::parse($value)->toFormattedDateString();
        }

        return Carbon::parse($value)->diffForHumans();
    }
    /**
     * 获取用户更新的时间，默认100天前输出完整时间，否则输出人性化的时间。
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {

        if (Carbon::now() > Carbon::parse($value)->addDays(100)) {
            return Carbon::parse($value)->toFormattedDateString();
        }

        return Carbon::parse($value)->diffForHumans();
    }
    /**
     * 获取用户登录的时间，默认100天前输出完整时间，否则输出人性化的时间。
     *
     * @param  string  $value
     * @return string
     */
    public function getLastSignInAttribute($value)
    {

        if (Carbon::now() > Carbon::parse($value)->addDays(100)) {
            return Carbon::parse($value)->toFormattedDateString();
        }

        return Carbon::parse($value)->diffForHumans();
    }
    /**
     * 找回密码发送邮件。
     *
     * @param Object $token
     *
     */
    public function sendPasswordResetNotification($token)
    {
        $data = [
            'url'=>url('password/reset/'.$token),
        ];
        $template = new SendCloudTemplate('zhihu_app_reset_pass',$data);
        Mail::raw($template,function($message){
            $message->from('Nomo@163.com','Nomo');
            $message->to($this->email);
        });
    }
    /**
     * 获取用户是否签到。
     *
     * @param
     * @return object
     */
    public function isSign()
    {
        $userid = $this->id;
        $date = date('Y-m-d');
        return DB::table('sign')->where([['user_id','=',$userid],['date','=',$date]])->first();
    }
    /**
     * 获取用户消息。
     *
     * @param
     * @return object
     */
    public function signCount()
    {
        $userid = $this->id;
        return DB::table('sign')->where([['user_id','=',$userid]])->count();
    }

    /**
     * 获取用户文章。
     *
     * @param
     * @return object
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    /**
     * 获取用户消息。
     *
     * @param
     * @return object
     */
    public function notifies()
    {
        return $this->hasMany(Notify::class);
    }
    /**
     * 获取用户购买文章。
     *
     * @param
     * @return object
     */
    public function buyPosts()
    {
        return $this->belongsToMany(Post::class,'orders','buy_user_id');
    }
    /**
     * 获取用户评论。
     *
     * @param
     * @return object
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * 获取用户角色。
     *
     * @param
     * @return object
     */
    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function giveRole(Role $role){
        return $this->roles()->save($role);
    }
    public function hasRole($role){
        if(is_string($role)){
            return $this->roles->contains('name',$role);
        }
        return !! $role->intersect($this->roles)->count();
    }
    public function roleBoard(){
        if(is_null($this->roles->first())){
            return 0;
        }
        $role = $this->roles->first()->name;
        $rolename = substr($role,strpos($role,'-')+1);
        return $rolename=='boardgod';
    }
    public function roleBoardId(){
        if(is_null($this->roles->first())){
            return 0;
        }
        $role = $this->roles->first()->name;
        $roleid = substr($role,0,strpos($role,'-'));
        return $roleid;
    }
    /**
     * 获取用户关注文章。
     *
     * @param
     * @return object
     */
    public function attentionPost()
    {
        return $this->belongsToMany(Post::class,'post_attention','user_id','post_id')->withTimestamps();
    }
    /**
     * 获取用户关注文章。
     *
     * @param
     * @return object
     */
    public function attentionBoard()
    {

        return $this->belongsToMany(Board::class,'board_attention')->withTimestamps();
    }
    /**
     * 关注某文章。
     *
     * @param  Post object
     * @return
     */
    public function setPostAttention(Post $post){

        return $this->attentionPost()->save($post);
    }
    /**
     * 取消关注某文章。
     *
     * @param  Post object
     * @return
     */
    public function deletePostAttention(Post $post){

        return $this->attentionPost()->detach($post->id);
    }
    /**
     * 取消关注某板块。
     *
     * @param  Board object
     * @return
     */
    public function deleteBoardAttention(Board $board){
        return $this->attentionBoard()->detach($board->id);
    }
    /**
     * 关注某板块。
     *
     * @param  Board object
     * @return
     */
    public function BoardAttention(Board $board){

        return $this->attentionBoard()->save($board);
    }
    public function banUser($datetime ){
        $redis = app('redis.connection');
        $datetimestring = $datetime;
        $datetime = Carbon::parse($datetimestring)->diffForHumans(Carbon::now());
        $datedisplay = Carbon::parse($datetimestring)->toDateString();
        $second = Carbon::parse($datetimestring)->timestamp-Carbon::now()->timestamp;
        if($redis->setnx('User:Ban:'.$this->id, $datetime.',到'.$datedisplay.'截止。')){
            $redis->expire('User:Ban:'.$this->id, $second);
        }
        return $redis->get('User:Ban:'.$this->id);
    }
    public function isBan(){
        $redis = app('redis.connection');
        return $redis->get('User:Ban:'.$this->id);
    }
    public function delBan(){
        $redis = app('redis.connection');
        return $redis->del('User:Ban:'.$this->id);
    }
}
