@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="jumbotron"
                 style="background-image: url({{$system->system_banner}});background-size:100% 100% ;">
                {{--<h2 style="color: #d9edf7">{{$system->system_title}}</h2>--}}
                <h2 style="color: #ffffff">{{$system->system_title}}</h2>

                <p style="color: #ffffff">{{date('Y.m.d')}}</p>
                {{--<p style="color: #d9edf7">{{date('Y.m.d')}}</p>--}}

                <a style="margin-right: 25px" class="btn btn-info " href="post/new" role="button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>  试试写点</a><a
                        style="margin-right: 25px" class="btn btn-success" href="post/new?type=question" role="button"><span class="glyphicon glyphicon-send" aria-hidden="true"></span>  不懂就问</a>
            </div>

        </div>
        <div class="row">

            <div class="col-md-8 ">

                <div class="panel panel-default" >
                    <div class="panel-heading">
                        <ul class=" nav nav-pills nav-justified">
                            @foreach($boards as $board)
                                @if($loop->index==4)
                                    <li><a href="/boards">更多...</a></li>
                                    @break
                                @endif
                                @if ($loop->first)
                                    <li class="active" style="li.active:"><a href="#{{$board->url}}"
                                                                             data-toggle="tab">{{$board->name}}</a>
                                    </li>
                                    @continue
                                @endif
                                <li><a href="#{{$board->url}}" data-toggle="tab">{{$board->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div id="myTabContent" class="tab-content">

                            @foreach($boards as $board)
                                @if($loop->index==4)
                                    @break
                                @endif
                                @if ($loop->first)
                                    <div class="tab-pane active in" id="{{$board->url}}">
                                        <div class="list-group">
                                            @foreach($board->posts as $post)
                                                @if($loop->index==10 )
                                                    <a href="board/{{$board->url}}">
                                                        <div class="list-group-item-info text-center">
                                                            <span class="glyphicon glyphicon-menu-down " aria-hidden="true">
                                                                <strong>查看更多</strong>
                                                            </span>
                                                        </div>
                                                    </a>

                                                    @break
                                                @endif
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
                                        </div>
                                    </div>
                                    @continue
                                @endif
                                <div class="tab-pane" id="{{$board->url}}">
                                    <div class="list-group">
                                        @foreach($board->posts as $post)
                                            @if($loop->index==10)
                                                <a href="board/{{$board->url}}">
                                                    <div class="list-group-item-info text-center">
                                                            <span class="glyphicon glyphicon-menu-down " aria-hidden="true">
                                                                <strong>查看更多</strong>
                                                            </span>
                                                    </div>
                                                </a>

                                                @break
                                            @endif
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
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="panel panel-default" >
                    <div class="panel-heading text-center"><h3 class="panel-title">每日任务</h3></div>

                    <div class="panel-body">
                        @guest


                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4><strong>每日任务：</strong>登录(0/1)</h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            @foreach($tasks as $task)
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h4><strong>每日任务：</strong>{{$task->task_content()}}({{$task->progress_number()}}/{{$task->progress_size()}})<span style=" float: right;color: #e95353"><span  class="glyphicon glyphicon-yen" aria-hidden="true"></span> {{$task->progress_size()*10}}</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-@if(($task->progress_number()/$task->progress_size())=='1')success @else info @endif info progress-bar-striped" role="progressbar" aria-valuenow="{{($task->progress_number()/$task->progress_size())*100}}" aria-valuemin="0" aria-valuemax="100" style="width:{{($task->progress_number()/$task->progress_size())*100}}%">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        @endguest
                    </div>
                </div>
                <div class="panel panel-default" >
                    <div class="panel-heading text-center"><h3 class="panel-title">问题推荐</h3></div>

                    <div class="panel-body">

                        <div class="panel panel-default">
                            <div class="panel-body">
                                @if($question)
                                <h4><strong>问：</strong>{{$question->title}}<span style=" float: right;color: #5ff4d2"><a href="/post/{{$question->createdAt()}}/{{$question->url}}"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span></a></span></h4>
                                @else
                                <h4>等等吧</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4" id="app">
                <div class="panel panel-default">
                    {!! Form::open(['url'=>'search/']) !!}
                    <div class="panel-body">
                        <div class="input-group">

                            {!! Form::text('content',null,['class'=>'form-control']) !!}
                            {{--<input type="text" class="form-control" placeholder="Search for...">--}}
                             <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Go!</button>
                            </span>

                        </div><!-- /input-group -->
                    </div>
                    {!! Form::close() !!}
                </div>
                @guest
                <div class="panel panel-default">
                    <div class="panel-heading text-center">消息</div>

                    <div class="panel-body">
                        <p>登录后查看消息</p>
                    </div>
                </div>
                @else
                    @can('Admin',Auth::user())
                    <div class="panel panel-default">
                        <div class="panel-heading text-center"><h3 class="panel-title">控制台</h3></div>

                        <div class="panel-body">
                            <div class="col-md-6">
                                <div align="center">
                                    <img style="border-radius:50%;overflow:hidden; display:inline;height: 80px;width: 80px;  box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px"
                                         src="{{Auth::user()->avatar}}">

                                    <div class="row"><h3><span class="label label-info">{{Auth::user()->roles->first()->label}}</span></h3>
                                </div>
                                {{--<div class="row">--}}
                                    {{--@foreach(Auth::user()->roles as $role)--}}
                                        {{--<p class="badge">{{$role->label}}</p>--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <a href="/setting/post  " class="btn btn-sm btn-block btn-default"><span class="glyphicon glyphicon-book" aria-hidden="true"></span>文章管理</a>
                                <a href="/setting/board" class="btn btn-sm btn-block btn-default"><span class="glyphicon glyphicon-th" aria-hidden="true"></span>板块板块</a>
                                <a href="/setting/user" class="btn btn-sm btn-block btn-default"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>用户管理</a>

                            </div>
                        </div>
                        </div>
                    </div>
                    @endcan

                    @if(Auth::user()->roleBoard())
                        <div class="panel panel-default">
                            <div class="panel-heading text-center"><h3 class="panel-title">控制台</h3></div>
                            <div class="panel-body">
                                <div class="col-md-6">
                                    <div align="center">
                                        <img style="border-radius:50%;overflow:hidden; display:inline;height: 80px;width: 80px;  box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px"
                                             src="{{Auth::user()->avatar}}">

                                        <div class="row"><h3><span class="label label-info">{{Auth::user()->roles->first()->label}}</span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <a href="/setting/post" class="btn btn-sm btn-block btn-default"><span class="glyphicon glyphicon-book" aria-hidden="true"></span>文章管理</a>
                                        <a href="/setting/board/{{Auth::user()->roleBoardId()}}" class="btn btn-sm btn-block btn-default"><span class="glyphicon glyphicon-th" aria-hidden="true"></span>板块板块</a>
                                        <a href="/setting/user" class="btn btn-sm btn-block btn-default"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>用户管理</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <app></app>

                    @endguest
                    <div class="panel panel-default">
                        <div class="panel-heading text-center"><h3 class="panel-title">﻿<strong>ε≡٩(๑>₃<)۶</strong>等待回复</h3></div>

                        <div class="panel-body">
                            <div class="list-group">
                                @foreach($zeroPost as $post)
                                    <button type="button" class="list-group-item">
                                        <a style="color:#33392b"
                                           href="/post/{{$post->createdAt()}}/{{$post->url}}">{{$post->title}}</a>

                                        <span style="color: #99cb84 "> | {{$post->updated_at}}</span>
                                        <div style="float: right">
                                            <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>   -
                                            @foreach($post->tags as $tag)


                                                @if($loop->index>=2)
                                                    <span >..</span>
                                                    @break
                                                @elseif($loop->last)
                                                    <span>{{$tag->name}}</span>
                                                    @Continue
                                                @endif
                                                <span>{{$tag->name}}、</span>

                                            @endforeach
                                        </div>

                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

@endsection
