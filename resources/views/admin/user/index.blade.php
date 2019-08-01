@extends('layout.common')
@section('content')
        <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="{{url('admin/user/index')}}" method="get">
    用户姓名：   <input type="text" name="user_name" value="{{$user_name}}">

    用户权限：
            <input type="radio" name="status" value="1" @if($status==1)checked @endif>
            <input type="radio" name="status" value="2" @if($status==2)checked @endif>
            <input type="radio" name="status" value="3" @if($status==3)checked @endif>

    <input type="submit" value="搜索">
</form>
<table border="1">
    登陆后用户名为{{Session::get('user_name')}}
    <tr>
        <td>ID</td>
        <td>用户姓名</td>
        <td>用户权限</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td>{{$v->user_id}}</td>
            <td>{{$v->user_name}}</td>
            <td>@if($v->status==1)用户 @elseif($v->status==2)管理员@elseif($v->status==3)禁用用户@endif</td>
            <td>
                <a href="{{url('/admin/user/edit',['user_id'=>$v->user_id])}}">修改</a>
                <a href="{{url('/admin/user/delete',['user_id'=>$v->user_id])}}">删除</a>
                {{--<a href="del/{{$v->student_id}}">删除</a>--}}
            </td>
        </tr>
    @endforeach
</table>
{{ $data->appends(['user_name' => $user_name,'status'=>$status])->links() }}
</body>
</html>
@endsection