<!DOCTYPE html>
<html>
<head>
    <title>微信分销用户列表</title>
</head>
<body>
<center>

    <table border="1">
        <tr>
            <td>用户user_id</td>
            <td>用户专属推广码</td>
            <td>用户专属二维码</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v->id}}</td>
                <td>{{$v->agent_code}}</td>
                <td>
                    <img width="150px" src="{{$v->qrcode_url}}"></img>
                </td>
                <td>
                    <a href="{{url('admin/fenxiao/create_qrcode')}}?id={{$v->id}}">生成用户专属二维码</a> |
                    <a href="{{url('admin/fenxiao/agent_list')}}?id={{$v->id}}">用户推广用户列表</a>
                </td>
            </tr>
        @endforeach
    </table>

</center>
</body>
</html>