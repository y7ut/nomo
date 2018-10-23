<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    //
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
    public function scopeShow($query){
        return $query->where([['read_status','1'],['created_at','>',Carbon::now()->subWeek()]])->orWhere('read_status','0');
    }
}
