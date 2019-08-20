@extends('layout.index')
@section('title', '注册')
@section('content')
<!-- register -->
<div class="pages section">
    <div class="container">
        <div class="pages-head">
            <h3>REGISTER</h3>
        </div>
        <div class="register">
            <div class="row">
                <form class="col s12" action="{{url("index/login/do_register")}}"  method="post">
                    @csrf
                    <div class="input-field">
                        <input type="text" name="user_name" class="validate" placeholder="NAME" required>
                    </div>
                    <div class="input-field">
                        <input type="password" name="user_pwd" placeholder="PASSWORD" class="validate" required>
                    </div>
                    <div class="input-field">
                        <input type="password" name="user_repwd" placeholder="RE_PASSWORD" class="validate" required>
                    </div>
                    <button class="btn button-default">REGISTER1</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end register -->
    @endsection


