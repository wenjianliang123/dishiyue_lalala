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
<form action="{{url("admin/user/update")}}">

        <input type="hidden" name="user_id" value="{{$data1->user_id}}">
        @csrf
        <table border="1" align="center">
            <tr>
                <td>用户姓名</td>
                <td><input type="text" name="user_name" value="{{$data1->user_name}}"></td>
            </tr>
            <tr>
                <td>用户密码</td>
                <td><input type="password" name="user_pwd" value="{{$data1->user_pwd}}"></td>
            </tr>
            <tr>
                <td>用户权限</td>
                <td>
                    <input type="radio" name="status" id="" value="1" @if($data1->status == 1)checked @endif>普通用户
                    <input type="radio" name="status" id="" value="2" @if($data1->status == 2)checked @endif>管理员
                    <input type="radio" name="status" id="" value="3" @if($data1->status == 3)checked @endif>禁用用户
                </td>
            </tr>

            <tr>
                <td colspan="2"><input type="submit" value="修改"></td>
            </tr>
        </table>

</form>
</body>
</html>
@endsection