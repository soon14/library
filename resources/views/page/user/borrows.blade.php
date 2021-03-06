@extends('layouts.main')

@section('title','我的借阅')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2 col-sm-2 sideBar">
            <ul>
                <li><a href="{{ route('contributions') }}">我的共享</a></li>
                <li class="sideBarActive"><a href="{{ route('borrows') }}">我的借阅</a></li>
                <li><a href="{{ route('settings') }}">我的账号</a></li>
            </ul>
        </div>
        <div class="col-md-10 col-sm-10">
            <div class="row">
                <span class="empty_notice">我的借阅，内容没写</span>
            </div>
        </div>
    </div>
</div>
@endsection