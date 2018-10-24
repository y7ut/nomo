@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                <li><a href="#">控制面板</a></li>
                <li class="active"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>专栏设置</li>
            </ol>

        </div>
        <div class="row">
            <div class="col-md-2" id="app">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="nav nav-pills  nav-stacked " role="tablist">
                            <li role="presentation" class="active"><a href="#board" aria-controls="board" role="tab" data-toggle="tab">板块列表</a></li>
                            <li role="presentation"><a href="#tag" aria-controls="tag" role="tab" data-toggle="tab">主题节点</a></li>
                            <li role="presentation"><a href="#new" aria-controls="new" role="tab" data-toggle="tab">系统设置</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10 ">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="board">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="bs-example" data-example-id="collapse-accordion">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div  role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="panel-heading" role="tab" id="headingOne">
                                                <h4 class="panel-title">
                                                    <a class="collapsed">
                                                        <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>  新增专栏<span style="float: right" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                                                <div class="panel-body">
                                                    {!! Form::open(['url'=>'setting/board/','files'=>true]) !!}
                                                    <div class="form-group">
                                                        {!! Form::label('name','专栏名称:') !!}
                                                        {!! Form::text('name',null,['class'=>'form-control']) !!}
                                                    </div>
                                                    <div class="form-group">
                                                        {!! Form::label('url','url地址:') !!}
                                                        {!! Form::text('url',null,['class'=>'form-control']) !!}
                                                    </div>
                                                    <div class="form-group">
                                                        {!! Form::label('intro','专栏简介:') !!}
                                                        {!! Form::textarea('intro',null,['class'=>'form-control']) !!}
                                                    </div>
                                                    <div class="form-group">
                                                        {!! Form::label('banner','Banner:') !!}

                                                        {!! Form::file('banner',['class'=>'form-control']) !!}
                                                    </div>
                                                    <div class="btn-toolbar list-toolbar">
                                                        {!! Form::submit('立即发布',['class'=>'btn btn-success ']) !!}
                                                        <a href="/" data-toggle="modal" class="btn btn-danger">返回</a>
                                                    </div>
                                                    {!! Form::close() !!}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>专栏名</th>
                                        <th>Url</th>
                                        <th>简介</th>
                                        <th>管理人</th>
                                        <th>缩略图</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($boards as $board)
                                    <tr>
                                        <th scope="row">{{$loop->index+1}}</th>
                                        <td>{{$board->name}}</td>
                                        <td>{{$board->url}}</td>
                                        <td>{{substr_replace($board->intro,'',12)}}...</td>
                                        <td>{{$board->boardGod()->name}}</td>
                                        <td>
                                            <img class="media-object" alt="64X64" src="{{$board->banner}}"
                                                 style="width: 128px;height: 72px; overflow:hidden; display:inline; margin:5px 0 5px 5px  ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; ">
                                            {{--./{{substr_replace($board->banner,'',0,9)}}--}}
                                        </td>
                                        <td>
                                            <a  href="/setting/board/{{$board->id}}"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a >
                                            @if($loop->index!=0)
                                            <a  href="/setting/board/{{$board->id}}/up"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></a >
                                            @endif

                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tag">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>主题</th>
                                        <th>创建者</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tags as $tag)
                                        <tr>
                                            <th scope="row">{{$loop->index+1}}</th>
                                            <td>{{$tag->name}}</td>
                                            <td>{{$tag->user->name}}</td>
                                            {!! Form::model($tag,['method'=>'DELETE','url'=>'/setting/tags/'.$tag->id]) !!}
                                            <button id="btn_delete_{{$tag->id}}" style="display: none"  type="submit" >永久删除</button>
                                            {!! Form::close() !!}
                                            <td><a onclick='javascript:return del({{$tag->id}});'><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="new">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {!! Form::open(['url'=>'setting/system','files'=>true]) !!}
                                <div class="form-group">
                                    {!! Form::label('content','首页标题:') !!}
                                    {!! Form::text('content',$system->system_title,['class'=>'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('banner','首页图片:') !!}
                                    <img class="media-object" alt="64X64" src="{{$system->system_banner}}"
                                         style="width: 60%;height: 60%; overflow:hidden; display:inline; margin:5px 0 5px 5px  ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; ">
                                    {!! Form::file('banner',null,['class'=>'form-control']) !!}
                                </div>
                                <div class="btn-toolbar list-toolbar">
                                    {!! Form::submit('立即发布',['class'=>'btn btn-success ']) !!}
                                    <a href="/" data-toggle="modal" class="btn btn-danger">返回</a>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @section('js')
                    <!-- 实例化编辑器 -->
            <script type="text/javascript">

                function del(tagid) {
                    var msg = "确定要删除么？";
                    if (confirm(msg)==true){
                        document.getElementById("btn_delete_"+tagid).click();
                    }else{
                        return false;
                    }
                }
            </script>
            @endsection
        </div>
    </div>
@endsection
