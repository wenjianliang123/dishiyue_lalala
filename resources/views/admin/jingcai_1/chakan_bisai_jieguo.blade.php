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
    <table align="center">
        <tr>
            <td align="center"><h1>比赛结果</h1></td>
        </tr>
        <tr>
            <td><h2>{{$data->qiudui_1}}  @if($data->jieguo==1)胜@elseif($data->jieguo==2)负@elseif($data->jieguo==3)平@endif  {{$data->qiudui_2}}</h2></td>
        </tr>
        <tr>
            <td><h3 style="color: #D50000">竞猜结果：很抱歉，您未参与本次竞猜</h3></td>
        </tr>
    </table>
</body>
</html>