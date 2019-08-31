@extends('layout.common')
@section('title', '自定义菜单添加')
@section('content')
    <!-- register -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
                <h3>自定义菜单添加</h3>
            </div>
            <div class="register">
                <div class="row">
                    <form class="col s12" action="{{url("wechat/custom_menu/menu_do_add")}}"  method="post">
                        @csrf
                        <select name="menu_type" id="">
                            <option value="">请选择菜单类型</option>
                            <option value="1">一级菜单</option>
                            <option value="2">二级菜单</option>
                        </select>
                        <select name="event_type" id="">
                            <option value="">请选择事件类型</option>
                            <option value="click">click</option>
                            <option value="view">view</option>
                            <option value="location_select">location_select</option>
                        </select>
                        <div class="input-field">
                            <input type="text" name="first_menu_name" class="validate" placeholder="第一级菜单名称" required>
                        </div>
                        <div class="input-field">
                            <input type="text" name="second_menu_name" placeholder="第二级菜单名称" class="validate" >
                        </div>
                        <div class="input-field">
                            <input type="text" name="menu_remark" placeholder="菜单备注或view类型的url" class="validate" required>
                        </div>


                        <button class="btn button-default">添加菜单</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end register -->
@endsection


