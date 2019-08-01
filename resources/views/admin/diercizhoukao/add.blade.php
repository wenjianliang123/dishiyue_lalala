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
    <form action="{{url('admin/diercizhoukao/do_add')}}" method="post">
        @csrf
        <table border="1" align="center">
            <tr>
                <td>车次</td>
                <td><input type="text" name="checi"></td>
            </tr>
            <tr>
                <td>出发地</td>
                <td><input type="text" name="chufadi"></td>
            </tr>
            <tr>
                <td>到达地</td>
                <td><input type="text" name="daodadi"></td>
            </tr>
            <tr>
                <td>价钱</td>
                <td><input type="number" name="jiaqian"></td>
            </tr>
            <tr>
                <td>张数</td>
                <td><input type="number" name="zhangshu"></td>
            </tr>
            <tr>
                <td>出发时间</td>
                <td><input type="text" name="chufashijian"></td>
            </tr>
            <tr>
                <td>到达时间</td>
                <td><input type="text" name="daodashijian"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="添加">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>