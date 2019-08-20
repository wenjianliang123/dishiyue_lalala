@extends('layout.common')
@section('content')
<form action="{{url("admin/login/do_login")}}" method="post">
    <table border="1" align="center">
    @csrf
        <tr>
            <td>用户名</td>
            <td><input type="text" name="user_name" id=""></td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input type="password" name="user_pwd" id=""></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="提交">
            </td>
        </tr>
    </table>
</form>
@endsection
