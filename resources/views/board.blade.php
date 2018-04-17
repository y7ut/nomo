@extends('layouts.app')

@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="/">Nomo</a></li>
                <li class="active">板块</li>
            </ol>

        </div>
        @foreach($boards as $board)
            <div class="row">
                <div class="jumbotron" style="background-image: url({{$board->banner}});background-size:100% auto ;">
                    <h2 style="color: #ffffff">{{$board->name}}</h2>

                    <p style="color: #ffffff">{{$board->intro}}</p>

                    <p><a style="margin-right: 25px" class="btn btn-success btn-lg" href="board/{{$board->url}}" role="button">进入</a></p>
                </div>

            </div>
        @endforeach

    </div>
@endsection
