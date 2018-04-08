@extends('layouts.app')

@section('content')
    <a name="top" id="top"></a>

    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                <li><a href="/board/{{$post->board->url}}">{{$post->board->name}}</a></li>
                <li class="active"> <span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> {{$post->title}}  By：{{$post->user->name}}</li>
            </ol>

        </div>
        <div class="row">
            <div class="col-md-12 jumbotron"
                 style="background-image: url({{$post->background}});background-size:100% auto ;">

                <h2 style="color: #d9edf7">{{$post->title}}</h2>

                <p style="color: #d9edf7">{{$post->created_at}}</p>

                <p style="color: #d9edf7">{{$post->user->name}}</p>
                <button class="btn btn-{{array_rand(['success'=>'1','info'=>'2','danger'=>'3','warning'=>'4'])}}"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span></button>
                @foreach($post->tags as $tag)
                    <button class="btn btn-{{array_rand(['success'=>'1','info'=>'2','danger'=>'3','warning'=>'4'])}}"> {{$tag->name}}</button>
                @endforeach
                @if($post->isCharge())

                    @can('showCharge',$post,Auth::user())
                    <button class="btn btn-danger">优选</button>

                    @endcan
                    @cannot('showCharge',$post,Auth::user())
                    <a href="/order/{{$post->createdAt()}}/{{$post->url}}/{{$post->orderToken()}}"
                       class="btn btn-success">花费{{$post->integration_charge}}积分，立即购买</a>
                    @endcannot

                @else

                @endif
            </div>
            <div class="col-md-8 ">

                <div class="panel panel-default">
                    {{--<div class="panel-heading">{{$post->title}}</div>--}}
                    <div class="panel-body" style="overflow: auto">
                        @if($post->isCharge())
                            @can('showCharge',$post,Auth::user())
                            <h2 class="text-center">{{$post->title}}</h2>
                            {!! $post->content !!}
                            @endcan
                            @cannot('showCharge',$post,Auth::user())
                            内容被隐藏啦
                            @endcannot
                        @else
                            <h2 class="text-center">{{$post->title}}</h2>
                            {!! $post->content !!}
                        @endif
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading text-center"><h3 class="panel-title">关注</h3></div>
                    <div class="panel-body">
                        <div align="center">

                                <div class="alert alert-info alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">×</span></button>

                                    @if(count($post->attentionUser)==0)
                                        <strong>暂无关注，关注文章及时跟进，更新时将会得到通知哦。</strong>

                                    @else
                                        <strong>订阅关注文章后，更新时将会得到通知哦。</strong>
                                    @endif

                                </div>





                                <div class="panel panel-default">
                                    @if(!!!count($post->attentionUser)==0)
                                        <div class="panel-heading text-center"><h3 class="panel-title">当前{{count($post->attentionUser)}}人</h3></div>

                                    <div class="panel-body">
                                        @foreach($post->attentionUser as $userattention)
                                        <div class="col-xs-2 col-md-2">

                                                <img  alt="{{$userattention->name}}"  style="height: 100%; width: 100%; display: block;" src="{{$userattention->avatar}}" data-holder-rendered="true">
                                            <div class="caption">
                                                <p><strong>{{$userattention->name}}</strong></p>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                    @endif
                                    <div class="panel-footer">
                                        @can('sureAttention',$post,Auth::user())
                                        <a href="/post/attention/{{$post->createdAt()}}/{{$post->url}}/{{$post->orderToken()}}" class="btn btn-block btn-info ">关注</a>
                                        @endcan
                                        @cannot('sureAttention',$post,Auth::user())
                                        @if($post->attentionUser->contains(Auth::user()))
                                            <a href="/post/attentionoff/{{$post->createdAt()}}/{{$post->url}}/{{$post->orderToken()}}" class="btn btn-block btn-danger ">取消关注</a>
                                        @else
                                        <a href="/post/attention/{{$post->createdAt()}}/{{$post->url}}/{{$post->orderToken()}}" class="btn btn-block btn-info disabled ">关注</a>
                                        @endif
                                        @endcannot

                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading text-center"><h3 class="panel-title">评论</h3></div>
                    <div class="panel-body">
                        <div class="panel panel-default">

                            <div class="panel-body">

                                @if(count($post->comments)==0)
                                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <strong>暂无评论，快来抢沙发~获得双倍积分</strong>
                                    </div>
                                @endif
                                @foreach($post->comments as $comment)
                                    <hr>
                                    <div class="media">
                                        <div class="media-left" style="margin: 15px 15px">
                                            <a href="#">
                                                <img class="media-object" alt="64X64" src="{{$comment->user->avatar}}"
                                                     style="width: 64px;height: 64px; overflow:hidden; display:inline; margin:5px 0 5px 5px  ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; ">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">{!! $comment->content !!}</div>
                                            <div class="media-bottom"><span><strong>{{$comment->user->name}}</strong> 发表于 {{$comment->created_at}}</span>
                                                <button type="button"
                                                        class="btn btn-sm btn-{{array_rand(['success'=>'1','info'=>'2','danger'=>'3','warning'=>'4'])}}"
                                                        data-toggle="modal"
                                                        data-target="#myModalmyModal{{$comment->id}}">
                                                    回复
                                                </button>
                                            </div>


                                            <!-- Modal -->
                                            <div class="modal fade" id="myModalmyModal{{$comment->id}}" tabindex="-1"
                                                 role="dialog" aria-labelledby="myModalLabelmyModal{{$comment->id}}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                        aria-hidden="true">&times;</span></button>
                                                            <p class="modal-title" id="myModalLabel">
                                                                回复：{{$comment->user->name}}</p>
                                                        </div>
                                                        {!! Form::open(['url'=>'post/'.$post->id.'/comment/'.$comment->id]) !!}
                                                        <div class="modal-body">

                                                            <!-- 编辑器容器 -->
                                                            <div class="form-group">
                                                                {!! Form::textarea('content',null, ['class' => 'form-control']) !!}

                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Close
                                                            </button>
                                                            {!! Form::submit('立即发布',['class'=>'btn btn-success ']) !!}

                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @foreach($comment->allChildrencomments as $comment)
                                                <hr>
                                                <div class="media">
                                                    <div class="media-left" style="margin: 15px 15px">
                                                        <a href="#">
                                                            <img class="media-object" alt="64X64"
                                                                 src="{{$comment->user->avatar}}"
                                                                 style="width: 64px;height: 64px; overflow:hidden; display:inline; margin:5px 0 5px 5px  ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; ">
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <p class="media-heading">{{$comment->content}}</p>
                                                        <span><strong>{{$comment->user->name}}</strong> 发表于 {{$comment->created_at}}</span>
                                                        <button type="button"
                                                                class="btn btn-sm btn-{{array_rand(['success'=>'1','info'=>'2','danger'=>'3','warning'=>'4'])}}"
                                                                data-toggle="modal"
                                                                data-target="#myModalmyModal{{$comment->id}}">
                                                            回复
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="myModalmyModal{{$comment->id}}"
                                                             tabindex="-1" role="dialog"
                                                             aria-labelledby="myModalLabelmyModal{{$comment->id}}">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                        <p class="modal-title" id="myModalLabel">
                                                                            回复：{{$comment->user->name}}</p>
                                                                    </div>
                                                                    {!! Form::open(['url'=>'post/'.$post->id.'/comment/'.$comment->id]) !!}
                                                                    <div class="modal-body">

                                                                        <!-- 编辑器容器 -->
                                                                        <div class="form-group">
                                                                            {!! Form::textarea('content',null, ['class' => 'form-control']) !!}

                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default"
                                                                                data-dismiss="modal">Close
                                                                        </button>
                                                                        {!! Form::submit('立即发布',['class'=>'btn btn-success ']) !!}

                                                                    </div>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @foreach($comment->allChildrencomments as $comment)
                                                            <hr>
                                                            <div class="media">
                                                                <div class="media-left" style="margin: 15px 15px ">
                                                                    <a href="#">
                                                                        <img class="media-object" alt="64X64"
                                                                             src="{{$comment->user->avatar}}"
                                                                             style="width: 64px;height: 64px; overflow:hidden; display:inline; margin:5px 0 5px 5px ;box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px; ">
                                                                    </a>
                                                                </div>
                                                                <div class="media-body" style="word-wrap:break-word">
                                                                    <p class="media-heading">{{$comment->content}}</p>
                                                                    <span><strong>{{$comment->user->name}}</strong> 发表于 {{$comment->created_at}}</span>

                                                                </div>

                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="panel panel-default">

                            @guest


                            <div class="panel-body">
                                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">×</span></button>
                                    <p><strong>回复请先登录</strong></p>

                                    <p>Please login first.</p>
                                </div>
                            </div>

                            @else

                                @if($post->isCharge())
                                    @can('showCharge',$post,Auth::user())

                                    <div class="panel-body">
                                        {!! Form::open(['url'=>'/post/'.$post->id.'/comment']) !!}
                                                <!-- 编辑器容器 -->
                                        <div class="form-group">
                                            <script id="container" name="content" type="text/plain"></script>

                                        </div>
                                        <div class="btn-toolbar list-toolbar">
                                            {!! Form::submit('立即发布',['class'=>'btn btn-success ']) !!}
                                            <a href="/" data-toggle="modal" class="btn btn-danger">返回</a>
                                            <a class="btn btn-warning" href="#top" target="_self">返回顶部</a>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                    @endcan
                                    @cannot('showCharge',$post,Auth::user())

                                    <div class="panel-body">
                                        <div class="alert alert-warning alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <p><strong>请先使用积分购买，方可评论</strong></p>

                                            <p>Please buy this post first,then you can write you comments.</p>
                                        </div>
                                    </div>
                                    @endcannot
                                @elseif($post->discomment)
                                    <div class="panel-body">
                                        <div class="alert alert-warning alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span></button>
                                            <p><strong>该文章已经关闭评论</strong></p>

                                        </div>
                                    </div>
                                @else

                                    <div class="panel-body">
                                        {!! Form::open(['url'=>'/post/'.$post->id.'/comment']) !!}
                                                <!-- 编辑器容器 -->
                                        <div class="form-group">
                                            <script id="container" name="content" type="text/plain"></script>

                                        </div>
                                        <div class="btn-toolbar list-toolbar">
                                            {!! Form::submit('立即发布',['class'=>'btn btn-success ']) !!}
                                            <a href="/" data-toggle="modal" class="btn btn-danger">返回</a>
                                            <a class="btn btn-warning" href="#top" target="_self">返回顶部</a>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                @endif

                                @endguest


                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-4" id="app">
                @can('editPost',$post,Auth::user())
                <div class="panel panel-default">
                    <div class="panel-heading text-center"><h3 class="panel-title">仪表盘</h3></div>
                    <div class="panel-body">

                        <a href="/post/{{$post->createdAt()}}/{{$post->url}}/edit/{{$post->orderToken()}}" class="btn btn-block btn-success"><span class="glyphicon glyphicon-text-color" aria-hidden="true"></span>  修改内容</a>
                        <button type="button"
                                class="btn btn-block btn-danger"
                                data-toggle="modal"
                                data-target="#myModalmyModaldelete">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 删除文章
                        </button>

                        <!-- Modal -->
                        @if($post->discomment)
                            <a href="/post/{{$post->createdAt()}}/{{$post->url}}/opencomment/{{$post->orderToken()}}" class="btn btn-block btn-warning"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>  开启评论</a>
                        @else
                            <a href="/post/{{$post->createdAt()}}/{{$post->url}}/closecomment/{{$post->orderToken()}}" class="btn btn-block btn-warning"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>  关闭评论</a>
                        @endif
                    </div>
                    <div class="modal fade" id="myModalmyModaldelete"
                         tabindex="-1" role="dialog"
                         aria-labelledby="myModalmyModaldelete">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close"
                                            data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <p class="modal-title" id="myModalLabel">
                                       真的要删除么？删除后找回需花费积分</p>
                                </div>
                                {!! Form::open(['method'=>'post','url'=>'post/'.$post->id.'/delete']) !!}
                                <div class="modal-body">

                                    <!-- 编辑器容器 -->
                                    <div class="form-group text-center">

                                        <img src="{{captcha_src()}}" alt="" onclick="this.src='{{captcha_src()}}?'+Math.random()">


                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('captcha','验证码:') !!}
                                        {!! Form::text('captcha',null, ['class' => 'form-control']) !!}

                                    </div>
                                    {!! Form::hidden('id',$post->id, ['class' => 'form-control']) !!}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close
                                    </button>
                                    {!! Form::submit('立即删除',['class'=>'btn btn-success ']) !!}

                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endcan
                @cannot('editPost',$post,Auth::user())
                <div class="panel panel-default">
                    <div class="panel-heading text-center"><h3 class="panel-title">作者</h3></div>
                    <div class="panel-body">
                        <div align="center">
                            <img style="border-radius:50%;overflow:hidden; display:inline;height: 30%;width: 30%; margin:30px; box-shadow:rgba(255,255,255,1) 0 0 0 2px, rgba(0,0,0,1) 0 0 2px 2px"
                                 src="{{$post->user->avatar}}">

                            <a href="/post/{{Crypt::encrypt(str_random(10).$post->user->id)}}/"><div class="row"><h3><span class="label label-success">{{$post->user->name}}</span></h3></a>
                            </div>
                            <div class="row"><p class="badge">活跃</p>

                                <p class="badge">加入于{{$post->user->created_at}}</p></div>

                        </div>
                    </div>
                </div>
                @endcannot

                <app></app>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
            maximumWords: 100,
        });
        ue.ready(function () {
            //关闭字数统计

            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection
