<table border="1">
    <tr>
        <td>用户ID</td>
        <td>昵称</td>
        <td>操作</td>

    </tr>
    @for($i=0;$i<=(count($data))-1;$i++)
        <tr>
            <td>{{$data[$i]['id']}}</td>
            <td>{{$data[$i]['name']}}</td>
            <td>
                <a href="{{url('zhoukao/liuyan/push_liuyan_view',['id'=>$data[$i]['id']])}}">留言</a>
                {{--<a href="{{url('zhoukao/liuyan/push_liuyan',['id'=>$data[$i]['id']])}}">留言</a>--}}
            </td>
        </tr>
    @endfor
</table>

