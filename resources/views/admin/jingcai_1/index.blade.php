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
        <tr>
            <td colspan="2" align="center">
                <h1 align="center">竞猜列表</h1>
            </td>
        </tr>
        @foreach($data as $v)
        <tr>
            <td>{{$v->qiudui_1}}  VS  {{$v->qiudui_2}}</td>
            <td>
                @if(time()>$v->jieshu_time)
                    <a href="{{url('admin/jingcai/chakan_bisai_jieguo',['jingcai_id'=>$v->jingcai_id])}}"><button>查看结果</button></a>
                    @else
                    <a href="{{url('admin/jingcai/yonghu_guess',['jingcai_id'=>$v->jingcai_id])}}"><button>竞猜</button></a>
                    @endif

            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>