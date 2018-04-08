@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                    <li><a href="/boards">板块</a></li>
                    <li class="active">{{$board->name}}</li>

            </ol>

        </div>
        <div class="row">
            @if(isset($board->banner))
                <div class="jumbotron" style="background-image: url({{$board->banner}});background-size:100% auto ;">
                    @else
                        <div class="jumbotron" style="background-image: url(/storage/6-1F22011240B63.jpg);background-size:100% auto ;">
                            @endif


                            <h2 style="color: #d9edf7">{{$title}}</h2>


                            <p style="color: #d9edf7">{{$smtitle}}</p>

                            <a style="margin-right: 25px" class="btn btn-info " href="post/new" role="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>  随笔感悟</a><a
                                    style="margin-right: 25px" class="btn btn-success" href="post/new?type=question" role="button"><span class="glyphicon glyphicon-send" aria-hidden="true"></span>  技术答疑</a>

                            @if($board->attentionUser->contains(Auth::user()))
                                <a href="/board/{{$board->url}}/attentionoff" class="btn  btn-default"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>取消订阅</a>
                            @else
                                <a href="/board/{{$board->url}}/attention" class="btn  btn-default"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>订阅</a>
                            @endif
                        </div>

                </div>
                <div class="row">

                    <div class="col-md-8 ">

                        <div class="panel panel-default" >

                            <div class="panel-body">
                                @foreach($posts as $post)
                                    <button type="button" class="list-group-item"><img
                                                style=" border-radius:50%; overflow:hidden; display:inline;height: 25px;width: 25px; margin:5px 15px 0 5px; box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px"
                                                src="{{$post->user->avatar}}">
                                        @if($post->charge)
                                            <span class="label label-danger">优选</span>
                                        @elseif($post->posttype)
                                            <span class="label label-success">问答</span>
                                        @endif
                                        <a style="color:#33392b"
                                           href="/post/{{$post->createdAt()}}/{{$post->url}}">{{$post->title}}</a>

                                        <div style="float: right">

                                            @foreach($post->tags as $tag)


                                                @if($loop->index>=2)
                                                    <span class="badge">..</span>
                                                    @break
                                                @endif
                                                <span class="badge">{{$tag->name}}</span>

                                            @endforeach
                                            <span style="color: #f46660;" class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                                            <span style="color: #555555 ">{{$post->attention_count}}</span>
                                            <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                                            <span style="color: #555555 ">{{$post->comment_count}}</span>
                                            <span style="color: #99cb84 ">| {{$post->updated_at}}</span>
                                        </div>
                                    </button>
                                @endforeach
                                 {{$posts->links()}}
                            </div>


                        </div>

                    </div>

                    <div class="col-md-4" id="app">
                        <app></app>

                    </div>
                </div>
        </div>

@endsection
