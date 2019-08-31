<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
</head>
<body>
    <form action="{{url('dologin')}}" method="post">
    @csrf
        <table border=1>
            <tr>
                <th>用户名</th>
                <td><input type="text" name="user_name"></td>
            </tr>
            <tr>
                <th>密码</th>
                <td><input type="password" name="user_pwd"></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" value="登录"></td>
            </tr>
        </table>
    </form>
</body>
</html>