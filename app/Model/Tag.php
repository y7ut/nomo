<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable=['name','created_id'];

    public function posts(){
        return $this->belongsTo(Post::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'created_id');
    }
}
