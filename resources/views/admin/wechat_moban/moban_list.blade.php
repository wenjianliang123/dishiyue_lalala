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
    <table align="center">
        <tr>
            <td>序号</td>
            <td>模板id</td>
            <td>模板标题</td>
            <td>模板内容</td>
            <td>操作</td>
        </tr>

        @foreach($moban_list_info['template_list'] as $v)
        <tr>
            <td>{{1}}</td>
            <td>{{$v['template_id']}}</td>
            <td>{{$v['title']}}</td>
            <td>{{$v['content']}}</td>
            <td><button class="shanchu" template_id="{{$v['template_id']}}">删除</button></td>
        </tr>
        @endforeach
    </table>

    <script src="/jquery-3.3.1.js"></script>
    <script>
        $('.shanchu').click(function(){
            template_id=$(this).attr('template_id');
//            alert(template_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{url('admin/del_moban')}}",
                data:{template_id:template_id},
                type:'post',
                success:function(res){
                    console.log(res);
                    var msg = JSON.parse(res);
                    if(msg.code==1) {
                        alert(msg.font);
                        location.href="{{url('/admin/moban_list')}}";
                    }else{
                        alert(msg.font);
                        history.go(0);
                    }
                }
            });
        });
    </script>
</body>
</html>