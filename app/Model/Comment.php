<?php

namespace App;

use App\Scopes\CommentScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /**
     * 模型的「启动」方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CommentScope);
    }
    /**
     * 所有会被触发的关联。
     *
     * @var array
     */
    protected $touches = ['post'];

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
    public function fatherComment(){
        return $this->belongsTo(Comment::class,'father_id')->withoutGlobalScope(CommentScope::class);;
    }

    public function comments(){
        return $this->hasMany(Comment::class,'father_id')->withoutGlobalScope(CommentScope::class);
    }
    public function allChildrencomments()
    {
        return $this->comments()->with('allChildrencomments');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function createdAt(){
        return substr_replace(substr_replace(substr($this->original['created_at'],0,10),'',4,1),'',6,1);

    }
    /**
     * 获得此评论所属的文章。
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
