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
<form action="{{url('admin/jingcai/do_add')}}" method="post">
    @csrf
    <table border="1" align="center">
        <tr>
            <td><input type="text" name="qiudui_1">VS<input type="text" name="qiudui_2"></td>
        </tr>
        <tr>
            <td colspan="2">结束竞猜时间 <input type="datetime-local" name="jieshu_time"></td>
        </tr>
        <tr>
            <td colspan="2" align="right"><input type="submit" value="添加"></td>
        </tr>
    </table>
</form>
</body>
</html>