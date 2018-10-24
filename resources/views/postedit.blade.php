@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {!! Form::open(['url'=>'post','files'=>true]) !!}
            <div class="col-md-8 ">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="margin: 2px 0px 0px -3px" class="form-group">

                                <input type="text" name="title" class="form-control" placeholder='输入标题'>


                        </div>
                    </div>
                    <div class="panel-body">
                          <div class="form-group">
                            {!! Form::label('board_list','模块') !!}

                            <select name="board" class="board js-states form-control" id="board_list"
                                  ></select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('type_list','类型') !!}

                            <select name="type" class="type js-states form-control" id="type_list"
                            ></select>
                        </div>
                        <!-- 编辑器容器 -->
                        <div class="form-group">
                            <textarea name="content" rows="" cols="" id="neweditor"></textarea>
                        </div>
                        <div class="form-group">
                            {!! Form::label('tag_list','主题') !!}

                            <select name="tag[]" class="tag js-states form-control" id="tag_list"
                                    multiple="multiple"></select>
                        </div>

                        <div class="form-group">
                            {!! Form::label('background','背景Cover') !!}

                            {!! Form::file('background',['class'=>'form-control','id'=>'btn_cover','style'=>'display:none']) !!}
                            <button type="button" onclick="F_Open_dialog()">选择文件</button>

                        </div>
                        <div class="form-group">
                            {!! Form::label('intergation','优选') !!}

                            <div class="form-contorl">
                                   @if(Auth::user()->integration>=100)
                                         <div class="input-group">
                                    <span class="input-group-addon">
                                             <input type="checkbox" id="charge"  name='needintergation' >
                                     </span>
                                    <input type="number" id="chargenum" name='intergation' min="1" max="20"  class="form-control" placeholder='输入消耗积分'>
                             </div><!-- /input-group -->
                              <span class="help-block">优选对积分有限制，积分大于100方可设置哦</span>
                                        @else
                                         <div class="input-group">
                                    <span class="input-group-addon">
                                             <input type="checkbox" id="charge" name='needintergation' >
                                     </span>
                                    <input type="number"   id="chargenum"  name='integration' min="1" max="20" class="form-control" placeholder='输入消耗积分' disabled>
                             </div><!-- /input-group -->
                              <span class="help-block">优选对积分有限制，积分大于100方可设置哦</span>
                                        @endif
                          
                            </div>
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
                    <div class="panel-heading">公告</div>

                    <div class="panel-body">
                        <ul class="list">
                            <li>分享生活见闻, 分享知识</li>
                            <li>接触新技术, 讨论技术解决方案</li>
                            <li>为自己的创业项目找合伙人, 遇见志同道合的人</li>
                            <li>自发线下聚会, 加强社交</li>
                            <li>发现更好工作机会</li>
                            <li>甚至是开始另一个神奇的开源项目</li>
                        </ul>
                    </div>
                </div>
                <app></app>
            </div>
            @section('js')
                    <!-- 实例化编辑器 -->
            <script type="text/javascript">

                function F_Open_dialog()
                {
                    document.getElementById("btn_cover").click();
                }
                function getUrlParam(k) {
                    var regExp = new RegExp('([?]|&)' + k + '=([^&]*)(&|$)');
                    var result = window.location.href.match(regExp);
                    if (result) {
                        return decodeURIComponent(result[2]);
                    } else {
                        return null;
                    }
                }
                $(document).ready(function () {

                        $('.tag').select2({
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

                        $('.board').select2({
                        placeholder:'选择板块',
                        ajax: {
                            url: "/select/board",
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
                    var data = [{ id: 0, text: '文章随笔' }, { id: 1, text: '问题答疑' }];
                        $('.type').select2({
                        placeholder:'选择类型',
                        data: data
                    });
                    //从url判断类型
                    if(getUrlParam('type')=='question'){

                        $('.type').select2('val','1')
                    }
                    else{

                        $('.type').select2('val','0')
                    }
                    //初始获取类型
                    var type = $(".type option:selected").val();
                    //从select2事件中获取类型
                    $(".type").on("select2:select",function(){
                        var type = $(".type option:selected").val();
                        //根据类型禁用优选
                        if(type=='1'){
                            $('#charge').attr('disabled',true);
                            $('#chargenum').attr('disabled',true);
                        }
                        else{
                            $('#charge').attr('disabled',false);
                            $('#chargenum').attr('disabled',false);
                        }
                    });

                    if(type=='1'){
                        $('#charge').attr('disabled',true);
                        $('#chargenum').attr('disabled',true);
                    }
                });
            </script>
            @endsection
        </div>
    </div>
@endsection
