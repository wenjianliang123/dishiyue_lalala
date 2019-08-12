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
<center>
    <form action="{{url('wechat/biaoqian/Batch_tag_user_delete')}}" method="post">
        @csrf
        <input type="hidden" name="tag_id" value="{{$id}}">
    <table align="center" border="1">
        <input type="submit" value="批量取消该标签下的用户">
        <tr>
            <td colspan="3" align="center">标签ID &nbsp &nbsp {{$id}}</td>
        </tr>

        <tr>
            <td colspan="3" align="center">标签内容 &nbsp &nbsp{{$name}}</td>
        </tr>
        <tr>
            <td>全选 <input type="checkbox" class="all"></td>
            <td>序号</td>
            <td>用户open_id</td>
        </tr>

        @foreach($data['data']['openid'] as $k=>$v)
            <tr>
                <td>
                    <input type="checkbox" class="box" name="openid_list[]" value="{{$v}}">
                </td>
                <td>
                    {{$k+1}}
                </td>
                <td>{{$v}}</td>
            </tr>
        @endforeach

    </table>
    </form>

</center>

<script src="/jquery-3.3.1.js"></script>
<script>
    $('.all').click(function(){
        var status = $(this).prop('checked');
        $('.box').prop('checked',status);
    });

</script>
</body>
</html>