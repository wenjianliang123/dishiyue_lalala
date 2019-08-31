<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>门卫系统</title>
</head>
<body>
    <h1>车库管理系统</h1>
    小区车位：{{$data->sum}}&ensp;&ensp;&ensp;&ensp;
    @if($data->shengyu>0)
        剩余车位：{{$data->shengyu}}
    @else
        已经停满
    @endif
     <br>
    <a href="{{url('admin/cheku/carin')}}">车辆入库</a>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<a href="{{url('admin/cheku/carout')}}">车辆出库</a>
</body>
</html>