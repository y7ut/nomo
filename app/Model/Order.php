<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public function post(){
        return $this->belongsTo(Post::class,'post_id');
    }
    public function buyUser(){
        return $this->belongsTo(User::class,'buy_user_id');
    }
    public function ownerUser(){
        return $this->belongsTo(User::class,'owner_user_id');
    }

}
