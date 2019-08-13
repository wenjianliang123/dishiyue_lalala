<!DOCTYPE html>
<html>
<head>
    <title>微信分销用户列表</title>
</head>
<body>
<center>

    <table border="1">
        <tr>
            <td>编号</td>
            <td>用户id</td>
            <td>openid</td>
            <td>创建时间</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v['id']}}</td>
                <td>{{$v['user_id']}}</td>
                <td>
                    {{$v['openid']}}
                </td>
                <td>
                    {{$v['add_time']}}
                </td>
            </tr>
        @endforeach
    </table>

</center>
</body>
</html>