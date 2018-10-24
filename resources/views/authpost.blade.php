@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                <li><a href="#">控制面板</a></li>
                <li class="active"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>内容管理</li>
            </ol>

        </div>
        <div class="row">
            <div class="col-md-2" id="app">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="nav nav-pills  nav-stacked " role="tablist">
                            <li role="presentation" class="active"><a href="#user" aria-controls="user" role="tab" data-toggle="tab">内容列表</a></li>
                            <li role="presentation"><a href="#color" aria-controls="color" role="tab" data-toggle="tab">缩略图浏览</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10 ">
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane active" id="user">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>背景</th>
                                        <th>标题</th>
                                        <th>用户</th>
                                        <th>板块</th>
                                        <th>禁用整改</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $post)
                                        <tr>
                                            <th scope="row">{{$loop->index+1}}</th>
                                            @if($post->isColorBackgrond())
                                                <td><div style="height: 64px;width: 192px;background-color:{{$post->background}} ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; "></div></td>
                                            @else
                                                <td><img class="media-object" alt="48X48" src="{{$post->background}}"
                                                         style="width: 192px;height: 64px; overflow:hidden; display:inline ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; "></td>
                                            @endif
                                            <td>{{$post->title}}</td>
                                            <td>{{$post->user->name}}</td>
                                            <td>{{$post->board->name}}</td>
                                            @if($post->isNeedEdit())
                                                <td>整改中</td>
                                            @else
                                                <td><a style="color: #a94442" href="/setting/post/{{$post->id}}/edit"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></td>

                                            @endif
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="color">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table table-hover" style="border-radius:5px">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            {{--<th>背景</th>--}}
                                            <th>标题</th>
                                            <th>用户</th>
                                            <th>板块</th>
                                            <th>查看</th>
                                        </tr>
                                        </thead>
                                        <tbody style="color: #fff ">
                                        @foreach($posts as $post)
                                            @if($post->isColorBackgrond())
                                                <tr style="background-color:{{$post->background}};height: 96px; ">
                                            @else
                                                <tr style="background-image: url({{$post->background}});background-size:100% auto ;height: 96px; ">
                                                    @endif
                                                    <th scope="row"><h4>{{$loop->index+1}}<h3></th>
                                                    {{--@if($post->isColorBackgrond())--}}
                                                    {{--<td><div style="height: 64px;width: 192px;background-color:{{$post->background}} ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; "></div></td>--}}
                                                    {{--@else--}}
                                                    {{--<td><img class="media-object" alt="48X48" src="{{$post->background}}"--}}
                                                    {{--style="width: 192px;height: 64px; overflow:hidden; display:inline ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; "></td>--}}
                                                    {{--@endif--}}
                                                    <td><h4>{{$post->title}}</h4></td>
                                                    <td>{{$post->user->name}}</td>
                                                    <td>{{$post->board->name}}</td>
                                                    @if($post->isNeedEdit())
                                                        <td>整改中</td>
                                                    @else
                                                        <td><a style="color:#fff" class="btn"  href="/post/{{$post->createdAt()}}/{{$post->url}}"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span></a></td>

                                                    @endif
                                                </tr>
                                                @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    {{$posts->links()}}
                </div>

            </div>
        </div>
    </div>
@endsection
