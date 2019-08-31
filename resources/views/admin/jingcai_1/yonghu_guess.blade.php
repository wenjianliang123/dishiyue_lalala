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
<form action="{{url('admin/jingcai/do_guess')}}" method="post">
    @csrf
    <table align="center">
        <tr>
            <td align="center"><h1>我要竞猜</h1></td>
        </tr>
        <tr>
            <td align="center"><b>{{$data->qiudui_1}}   VS   {{$data->qiudui_2}}</b></td>
        </tr>
        <input type="hidden" name="jingcai_id" value="{{$data->jingcai_id}}">
        <tr>
            <td>
                <input type="radio" name="xuanze" value="1">胜
                <input type="radio" name="xuanze" value="2">负
                <input type="radio" name="xuanze" value="3">平
            </td>
        </tr>
        <tr>
            <td align="center">
                <input type="submit" value="提交">
            </td>
        </tr>
    </table>
</form>
</body>
</html>