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
    <table  align="center">
        @foreach($data as $v)
        <tr>
            <td>新闻标题</td>
            <td>{{$v['xinwen_name']}}</td>
        </tr>
        <tr>
            <td>作者</td>
            <td>{{$v['xinwen_zuozhe']}}</td>
        </tr>
        <tr>
            <td>访问量</td>
            <td>{{$fangwen}}</td>
        </tr>
        <tr>
            <td>新闻详情</td>
            <td><textarea name="xinwen_detail" id="" cols="30" rows="10">{{$v['xinwen_detail']}}</textarea></td>
        </tr>
            @endforeach
    </table>
</body>
</html>
