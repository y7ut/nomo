@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                <li><a href="/setting/board">控制面板</a></li>
                <li class="active"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>专栏设置</li>
            </ol>

        </div>
        <div class="row">
            {!! Form::model($board,['method'=>'DELETE','url'=>'/setting/board/'.$board->id]) !!}
            <button id="btn_delete" style="display: none" onclick='javascript:return del();' type="submit" >永久删除<span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
            {!! Form::close() !!}
            {!! Form::open(['method'=>'PATCH','url'=>'/setting/board/'.$board->id,'files'=>true]) !!}
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="margin: 2px 0px 0px -3px" class="form-group">

                            <h4 >板块设置：<strong>{{$board->name}} </strong><a onclick='javascript:return F_Open_dialog();'><span style="float: right" class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></h4>

                        </div>
                    </div>
                    <div class="panel-body">
                        @cannot('Admin',Auth::user())
                        <p style="font-weight: bold">管理人：</p><span  class="glyphicon glyphicon-user" aria-hidden="true">{{$board->boardGod()->name}}</span>
                        @endcannot
                        <div class="form-group">
                            @can('Admin',Auth::user())
                            {!! Form::label('user_list','管理人') !!}

                            <select name="user" class="user js-states form-control" id="user_list"
                            >
                                <option value="{{$board->boardGod()->id}}" selected="selected">{{$board->boardGod()->name}}</option>

                            </select>
                            @endcan
                        </div>

                        <!-- 编辑器容器 -->

                        <div class="form-group">
                            {!! Form::label('name','专栏名称:') !!}
                            {!! Form::text('name',$board->name,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            {!! Form::label('url','url地址:') !!}
                            {!! Form::text('url',$board->url,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('intro','专栏简介:') !!}
                            {!! Form::textarea('intro',$board->intro,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('banner','Banner:') !!}

                            {!! Form::file('banner',['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <img class="media-object" alt="64X64" src="{{$board->banner}}"
                                 style="width: auto;height: 256px; overflow:hidden; display:inline; margin:5px 0 5px 5px  ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; ">
                        </div>


                        <div class="btn-toolbar list-toolbar">
                            {!! Form::submit('立即发布',['class'=>'btn  btn-block btn-success ']) !!}

                            <a href="/" data-toggle="modal" class="btn btn-block btn-danger">返回</a>
                        </div>


                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            @section('js')
                    <!-- 实例化编辑器 -->
            <script type="text/javascript">
                function F_Open_dialog()
                {
                    document.getElementById("btn_delete").click();
                }
                function del() {
                    var msg = "确定要删除么？";
                    if (confirm(msg)==true){
                        return true;
                    }else{
                        return false;
                    }
                }
                $(document).ready(function () {
                    {{--$.ajax({--}}
                    {{--type: 'GET',--}}
                    {{--url: '/select/tag/' + '{{$post->id}}',--}}
                    {{----}}
                    {{--}).then(function (data) {--}}
                    {{----}}
                    {{--// create the option and append to Select2--}}
                    {{--var option = new Option(data.name, data.id, true, true);--}}
                    {{--tagSelect.append(option).trigger('change');--}}

                    {{--// manually trigger the `select2:select` event--}}
                    {{--tagSelect.trigger({--}}
                    {{--type: 'select2:select',--}}
                    {{--params: {--}}
                    {{--data: data--}}
                    {{--}--}}
                    {{--});--}}
                    {{--});--}}
                    $('.user').select2({
                        placeholder:'选择用户',
                        ajax: {
                            url: "/select/user",
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
