@extends('layouts.default')
@section('title','注册')
@section('content')
  <div class="offset-md-2 col-md-8">
    <div class="card">
      <div class="card-header">
        <h5>注册</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('users.store') }}" method="post">
          {{ csrf_field() }}
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
            <label for="name">名称：</label>
            <input class="form-control" name="name" type="text" value="{{ old('name') }}">
          </div>
          <div class="form-group">
            <label for="email">邮箱：</label>
            <input type="text" name="email" class="form-control" value="{{ old('email') }}">
          </div>
          <div class="form-group">
            <label for="password">密码：</label>
            <input type="password" name="password" class="form-control" value="{{ old('password') }}">
          </div>
          <div class="form-group">
            <label for="password-confirm">确认密码：</label>
            <input type="password" name="password-confirm" class="form-control" value="{{ old('password_confirm') }}">
          </div>

          <button class="btn btn-primary" type="submit">注册</button>
        </form>
      </div>
    </div>
  </div>
@stop
