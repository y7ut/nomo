<?php

namespace App;

use App\Scopes\UpdatedScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class Post extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['title', 'content','url','background'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UpdatedScope());
    }
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
     * 文章所有便签
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class,'post_tag','post_id','tag_id')->withTimestamps();
    }
    /**
     * 文章所有板块
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    /**
     * 文章购买者
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buyorders()
    {
        return $this->belongsToMany(User::class,'orders','post_id','buy_user_id')->withTimestamps();
    }
    /**
     * 文章作者
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * 关注人
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function attentionUser()
    {
        return $this->belongsToMany(User::class,'post_attention','post_id','user_id')->withTimestamps();
    }
    /**
     * 文章评论
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * 限制查询收费的文章。(本地作用域)
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCharge($query)
    {
        return $query->where('charge', '=', 1);
    }
    public function isCharge(){
        if($this->charge==1){
            return 1;
        }
    }
    /**
     * 限制查询文章。(本地作用域)
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQuestion($query)
    {
        return $query->where('posttype', '=', 1);
    }
    /**
     * 判断是否为问答类型文章
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function isQuestion(){
        if($this->question==1){
            return 1;
        }
    }
    /**
     * 获取文章日期格式格式为20180131这种
     *
     * @return string
     */
    public function createdAt(){
        return substr_replace(substr_replace(substr($this->original['created_at'],0,10),'',4,1),'',6,1);

    }
    /**
     * 文章行为token
     *
     * @return string
     */
    public function orderToken(){
        return Crypt::encrypt(str_random(10).Auth::id());

    }

}
