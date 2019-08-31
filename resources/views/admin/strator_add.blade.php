<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
   <form action="{{url('strator_add_do')}}" method="post">
   @csrf
      <table>
       <tr>
            <td>用户名</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>密码</td>
            <td><input type="password" name=pwd></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="登录"></td>
        </tr>
        </table>
   </form>
</body>
</html>