<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>标签列表</title>
</head>
<body>
<center>

    <h1>
        <a href="{{url('admin/create_biaoqian_view')}}">添加标签</a>
    </h1>

    <table align="center" border="1">
        <tr>
            <td>标签id</td>
            <td>标签内容</td>
            <td>此标签下粉丝数</td>
            <td>操作</td>
        </tr>

        @foreach($data['tags'] as $v)
            <tr>
                <td>{{$v['id']}}</td>
                <td>{{$v['name']}}</td>
                <td>{{$v['count']}}</td>
                <td>
                    <a href="{{url('admin/eidt_biaoqian_view',['id'=>$v['id']])}}">修改标签</a> ||
                    <a href="{{url('wechat/biaoqian/delete_tag',['id'=>$v['id']])}}">删除</a> ||
                    <a href="{{url('admin/get_tag_user_view',['id'=>$v['id']])}}">获取标签下粉丝列表</a> ||
                    <a href="{{url('wechat/user_list_zhanshi')}}?tag_id={{$v['id']}}">为粉丝打标签</a>||
                    <a href="{{url('admin/Batch_send_tag_user_info_view')}}?tag_id={{$v['id']}}">推送消息</a>||

                </td>
            </tr>
        @endforeach
    </table>

</center>
</body>
</html>