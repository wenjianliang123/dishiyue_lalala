@extends('layout.index')
@section('title', '登录')
@section('content')
<!-- login -->
<div class="pages section">
    <div class="container">
        <div class="pages-head">
            <h3>LOGIN</h3>
        </div>
        <div class="login">
            <div class="row">
                    <form action="{{url('index/login/do_login')}}" method="post" class="col s12"  >
                    @csrf
                    <div class="input-field">
                        <input type="text" class="validate" name="user_name" placeholder="USERNAME" required>
                    </div>
                    <div class="input-field">
                        <input type="password" name="user_pwd" class="validate" placeholder="PASSWORD" required>
                    </div>

                    <a href="{{url('wechat/login')}}"><p><b style="font-size:20px">第三方登录</b></p></a>
                    <a href=""><h6>Forgot Password ?</h6></a>
                    <button class="btn button-default">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end login -->
@endsection


