@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                <li><a href="/board/{{$post->board->url}}">{{$post->board->name}}</a></li>
                <li><a href="/post/{{$post->createdAt()}}/{{$post->url}}">{{$post->title}}  By：{{$post->user->name}}</a></li>
                <li class="active"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> 修改</li>
            </ol>

        </div>
        <div class="row">
            {!! Form::open(['method'=>'PATCH','url'=>'post/'.$post->id]) !!}
            <div class="col-md-8 ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="margin: 2px 0px 0px -3px" class="form-group">

                            <h4>内容修改：<strong>{{$post->title}}</strong></h4>

                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- 编辑器容器 -->
                        <div class="form-group">
                            <textarea name="content" id="editor">{{$post->content}}</textarea>
                        </div>
                        <div class="form-group">
                            {!! Form::label('tag_list','主题') !!}

                            <select name="tag[]" class="tag js-states form-control" id="tag_list"
                                    multiple="multiple" >
                                @foreach($post->tags as $tag)
                                <option value="{{$tag->id}}" selected="selected">{{$tag->name}}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="btn-toolbar list-toolbar">
                            {!! Form::submit('立即发布',['class'=>'btn btn-success ']) !!}
                            <a href="/" data-toggle="modal" class="btn btn-danger">返回</a>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="col-md-4" id="app">
                <div class="panel panel-default">
                    <div class="panel-heading">提示</div>

                    <div class="panel-body">
                        <ul class="list">
                            <li>您只能修改文章内容</li>
                            <li>或添加修改主题</li>
                            <li>如有需求请另起新内容</li>
                        </ul>
                    </div>
                </div>
                <app></app>
            </div>
            @section('js')
                    <!-- 实例化编辑器 -->
            <script type="text/javascript">
                $(document).ready(function () {
                    var tagSelect = $('.tag');
                    tagSelect.select2({
                        placeholder:'选择主题',
                        tags: true,
                        ajax: {
                            url: "/select/tag",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term,
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true
                        }
                    });

                });

            </script>
            @endsection
        </div>
    </div>
@endsection
