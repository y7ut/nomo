<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
    public function selectTag(){
        $tag = Tag::all()->map(function($tag){
            return [
                'id'=>$tag->id,
                'text'=>$tag->name
            ];
        })->toArray();

        return json_encode($tag);
    }
    public function selectPostTag($post_id){
        $tag = Post::find($post_id)->tags->map(function($tag){
            return [
                'id'=>$tag->id,
                'text'=>$tag->name
            ];
        })->toArray();

        return json_encode($tag);
    }
}
