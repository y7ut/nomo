<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($title))
        <title>{{ $title }}_Nomo</title>
    @else
        <title>{{ config('app.name', 'Laravel') }}</title>
    @endif
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus{
            background-color: #8eb4cb;
        }
    </style>
</head>
<body>
    <div>
        <nav class="navbar navbar-default navbar-static-top">
            <div  class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a  class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">登录</a></li>
                            <li><a href="{{ route('register') }}">注册</a></li>
                        @else
                            {{--<li>--}}
                                {{--<a href="#" class="dropdown-toggle">{{ Auth::user()->name }}</a>--}}
                            {{--</li>--}}



                                {{--<img style="height: 30% ;width: 30%;" src="{{Auth::user()->avatar}}">--}}

                            <li class="dropdown">

                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    <img style=" overflow:hidden; display:inline;height: 20px;width: 20px; margin:0;" class=" avatar-topnav " src="{{Auth::user()->avatar}}"> {{ Auth::user()->name }}<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/post/attentions"><span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>  订阅列表</a> </li>
                                    <li><a href="/post/{{Crypt::encrypt(str_random(10).Auth::id())}}/"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>  个人内容</a> </li>
                                    @if(Auth::user()->isSign())
                                        <li><a href="#"><span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span>  已签到{{Auth::user()->signin_count}}天</a>  </li>
                                        @else
                                        <li><a href="/user/sign"><span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span>  签到</a>  </li>
                                        @endif

                                    <li><a href="#">积分：<span style="color: #e95353"><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> {{ Auth::user()->integration }}</span></a>  </li>
                                    <li><a href="/setting/randpic"><span style="color: #b6a338" class="glyphicon glyphicon-fire" aria-hidden="true"></span>换一换</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>  登出
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            @include('flash::message')
        </div>
        @yield('content')
    </div>
    <footer class="footer" style="background-color: #fff">
        <div class="container" >
            <div class="row footer-top">

                <div class="col-sm-5 col-lg-5">

                    <p class="padding-top-xsm">我们是高品质的开发者技术社区，致力于为学生提供一个分享创造、结识伙伴、协同互助的平台。</p>


                    <br>
              <span style="font-size:0.9em">
                  Designed by <span style="color: #e27575;font-size: 14px;">❤</span> <a href="https://github.com/y7ut" target="_blank" style="color:inherit">谢宇轩</a>
              </span>
                </div>

                <div class="col-sm-6 col-lg-6 col-lg-offset-1">

                    <div class="row">
                        <div class="col-sm-4">
                            <h4>赞助商</h4>
                            <ul class="list-unstyled">
                                <a href="http://www.ucloud.cn/?utm_source=zanzhu&amp;utm_campaign=phphub&amp;utm_medium=display&amp;utm_content=yejiao&amp;ytag=phphubyejiao" target="_blank"><img src="https://lccdn.phphub.org/uploads/banners/bQawWl3vT5dc2lYx5JZ7.png" class="popover-with-html footer-sponsor-link" width="98" data-placement="top" data-content="本站服务器由 UCloud 赞助" data-original-title="" title=""></a>
                                <a href="http://www.qiniu.com/?utm_source=phphub" target="_blank"><img src="https://lccdn.phphub.org/uploads/banners/yGLIR0idW7zsInjsNmzr.png" class="popover-with-html footer-sponsor-link" width="98" data-placement="top" data-content="本站 CDN 服务由七牛赞助" data-original-title="" title=""></a>
                                <a href="https://www.upyun.com/" target="_blank"><img src="https://lccdn.phphub.org/uploads/banners/XPtLlZmIN1cQbLuDFEON.png" class="popover-with-html footer-sponsor-link" width="98" data-placement="top" data-content="Composer 镜像赞助商" data-original-title="" title=""></a>
                                <a href="http://www.sendcloud.net/" target="_blank"><img src="https://lccdn.phphub.org/uploads/banners/JpTCK6OKYBIrBIWdtob8.png" class="popover-with-html footer-sponsor-link" width="98" data-placement="top" data-content="订阅邮件赞助商：SendCloud" data-original-title="" title=""></a>
                            </ul>
                        </div>


                        <div class="col-sm-4">
                            <h4>其他信息</h4>
                            <ul class="list-unstyled">
                                <li><a href="#"><i class="fa fa-thumbs-up" aria-hidden="true"></i> 软件外包服务</a></li>
                                <li><a href="#"><i class="fa fa-globe text-md"></i> 推荐网站</a></li>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>
            <br>
            <br>
        </div>
    </footer>


    <!-- Scripts -->

    <script>var userid = {!! Auth::id() !!}
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('js')

</body>
</html>
