@extends('layout.common')
@section('content')
<form action="{{url("admin/login/do_register")}}"  method="post">
    @csrf
    <table align="center" border="1">
        <tr>
            <td>用户名</td>
            <td><input type="text" name="user_name"></td>
        </tr>

        <tr>
            <td>密码</td>
            <td><input type="password" name="user_pwd"></td>
        </tr>

        <tr>
            <td>确认密码</td>
            <td><input type="password" name="user_repwd"></td>
        </tr>

        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="注册">
            </td>
        </tr>
        </table>
</form>
@endsection