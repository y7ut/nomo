@extends('layouts.app')

@section('content')
    @include('vendor.ueditor.assets')
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

                                            <td><img class="media-object" alt="48X48" src="{{$post->background}}"
                                                     style="width: 192px;height: 64px; overflow:hidden; display:inline; margin:5px 0 5px 5px  ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; "></td>
                                            <td>{{$post->title}}</td>
                                            <td>{{$post->user->name}}</td>
                                            <td>{{$post->board->name}}</td>

                                            <td><a><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                {{$posts->links()}}
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
