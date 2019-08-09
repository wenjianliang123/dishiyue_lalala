<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
<center>

    <h1>
        <a href="{{url('admin/create_biaoqian_view')}}">添加标签</a>||
        {{--<a href="{{url('')}}">粉丝列表</a>--}}
        {{--<a href="{{url('wechat/get_user_list')}}">刷新用户列表</a>--}}
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
                    {{--<a href="{{url('')}}?id={{$v['id']}}">删除</a> |--}}
                    {{--<a href="{{url('')}}?id={{$v['id']}}">粉丝列表</a> |--}}
                    {{--<a href="{{url('')}}?tag_id={{$v['id']}}">为粉丝打标签</a>--}}

                    <a href="{{url('admin/eidt_biaoqian_view',['id'=>$v['id']])}}">修改标签</a> |
                    <a href="{{url('wechat/biaoqian/delete_tag',['id'=>$v['id']])}}">删除</a> |
                    {{--<a href="{{url('wechat/biaoqian/get_tag_user',['id'=>$v['id']])}}">获取标签下粉丝列表</a> |--}}
                    <a href="{{url('admin/get_tag_user_view',['id'=>$v['id']])}}">获取标签下粉丝列表</a> |
                    {{--<a href="{{url('admin/get_tag_user_view')}}?id={{$v['id']}}">获取标签下粉丝列表</a>--}}
                </td>
            </tr>
        @endforeach
    </table>

</center>

<script src="/jquery-3.3.1.js"></script>
{{--<script>
    $('.xiugai').click(function(){
        _this=$(this);
        id=_this.attr('id');
        name=_this.attr('name');
            alert(id);
            alert(name);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{url('admin/eidt_biaoqian_view')}}",
            data:{id:id,name:name},
            type:'post',
            success:function(res){
                console.log(res);
                --}}{{--var msg = JSON.parse(res);--}}{{--
                --}}{{--if(msg.code==1) {--}}{{--
                    --}}{{--alert(msg.font);--}}{{--
                    --}}{{--location.href="{{url('/admin/moban_list')}}";--}}{{--
                --}}{{--}else{--}}{{--
                    --}}{{--alert(msg.font);--}}{{--
                    --}}{{--history.go(0);--}}{{--
                --}}{{--}--}}{{--
            }
        });
    });
</script>--}}
</body>
</html>