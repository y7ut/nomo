@extends('layouts.app')

@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                <li><a href="#">控制面板</a></li>
                <li class="active"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>用户设置</li>
            </ol>

        </div>
        <div class="row">
            <div class="col-md-2" id="app">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="nav nav-pills  nav-stacked " role="tablist">
                            <li role="presentation" class="active"><a href="#user" aria-controls="user" role="tab" data-toggle="tab">用户列表</a></li>
                            <li role="presentation"><a href="#new" aria-controls="new" role="tab" data-toggle="tab">系统消息</a></li>
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
                                        <th>头像</th>
                                        <th>用户名</th>
                                        <th>邮箱</th>
                                        <th>积分</th>
                                        <th>累计签到天数</th>
                                        <th>上次登录</th>
                                        <th>封禁状态</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <th scope="row">{{$loop->index+1}}</th>

                                            <td><img class="media-object" alt="48X48" src="{{$user->avatar}}"
                                                     style="width: 48px;height: 48px; overflow:hidden; display:inline; margin:5px 0 5px 5px  ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; "></td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->integration}}</td>
                                            <td>累计签到过{{$user->signCount()}}天</td>
                                            <td>{{$user->lastsignin}}</td>
                                            <button type="button"
                                                    id="button_ban_{{$user->id}}"
                                                    style="display: none"
                                                    data-toggle="modal"
                                                    data-target="#myModalmyModal{{$user->id}}">
                                            </button>
                                            <div class="modal fade" id="myModalmyModal{{$user->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="myModalLabelmyModal{{$user->id}}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                        aria-hidden="true">&times;</span></button>
                                                            <p class="modal-title" id="myModalLabel">
                                                                封禁：{{$user->name}}</p>
                                                        </div>
                                                        {!! Form::open(['url'=>'setting/user/'.$user->id]) !!}
                                                        <div class="modal-body">

                                                            <!-- 编辑器容器 -->
                                                            <div class="form-group">
                                                                {!! Form::label('datetime','封禁到') !!}
                                                                {!! Form::date('datetime',null, ['class' => 'form-control']) !!}

                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            {!! Form::submit('确定',['class'=>'btn btn-success ']) !!}

                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @if($user->isban())
                                                <td>封禁至{{$user->isban()}}</td>
                                                <td><a href="/setting/user/{{$user->id}}/outban">解封</a></td>
                                            @else
                                                <td><span onclick='javascript:return F_Open_dialog_{{$user->id}}();'><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></span></td>
                                            @endif

                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                {{$users->links()}}
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="new">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                {!! Form::open(['url'=>'setting/notify']) !!}
                                <div class="form-group">
                                    {!! Form::label('content','通知详情:') !!}
                                    {!! Form::text('content',null,['class'=>'form-control']) !!}
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
                @foreach($users as $user)
                function F_Open_dialog_{{$user->id}}() {
                    document.getElementById("button_ban_{{$user->id}}").click();
                }
                @endforeach

            </script>
            @endsection
        </div>
    </div>
@endsection
