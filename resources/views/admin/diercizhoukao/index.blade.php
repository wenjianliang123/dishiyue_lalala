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
<form action="{{url('admin/diercizhoukao/index')}}" method="get">
    出发地：   <input type="text" name="chufadi" value="{{$chufadi}}">
    到达地：   <input type="text" name="daodadi" value="{{$daodadi}}">
    <input type="submit" value="搜索">
</form>
<table border="1">
    <tr>
        <td>车次</td>
        <td>出发地</td>
        <td>到达地</td>
        <td>价钱</td>
        <td>张数</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td>{{$v->checi}}</td>
            <td>{{$v->chufadi}}</td>
            <td>{{$v->daodadi}}</td>
            <td>{{$v->jiaqian}}元</td>
            <td>@if($v->zhangshu>100)有 @elseif($v->zhangshu==0)无 @else{{$v->zhangshu}}@endif</td>
            <td>
                <button @if($v->zhangshu==0)disabled="" @endif>购买</button>
            </td>
        </tr>
    @endforeach
</table>
{{ $data->appends(['chufadi' => $chufadi,'daodadi'=>$daodadi])->links() }}
</body>
</html>