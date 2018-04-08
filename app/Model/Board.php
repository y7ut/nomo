<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    //

    public function posts(){
        return $this->hasMany(Post::class);
    }

    /**
     * 关注人
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function attentionUser()
    {
        return $this->belongsToMany(User::class,'board_attention','board_id','user_id')->withTimestamps();
    }
    public function boardGod(){
        $user = Permission::where('name','=',$this->id)->get()->map(function($permission){
            return $permission->roles->filter(function($item){
                return $item->name!='god';
            })->map(function($item){
                return $item->users;
            });
        })->flatten()->first();
        //如果没有找到那么变成默认的god角色的用户
        if(is_null($user)){
            $user = Permission::where('name','=','Admin')->get()->map(function($permission){
                return $permission->roles->map(function($item){
                    return $item->users;
                });
            })->flatten()->first();
        }
        return $user;
    }
    public function boardRole(){
        $role = Permission::where('name','=',$this->id)->get()->map(function($permission){
            return $permission->roles->filter(function($item){
                return $item->name!='god';
            });
        })->flatten()->first();
        return $role;
    }


}
