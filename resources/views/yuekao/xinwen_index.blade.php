<table border="1">
        <tr>
            <td>新闻ID</td>
            <td>新闻标题</td>
            <td>新闻图片</td>
            <td>新闻作者</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v->xinwen_id}}</td>
                <td>{{$v->xinwen_name}}</td>
                <td><img src="{{asset($v->xinwen_picture)}}" width="100" alt=""></td>
                <td>{{$v->xinwen_zuozhe}}</td>
                <td>{{date('Y-m-d H:i:s',$v->add_time)}}</td>
                <td>
                    <a href="del/{{$v->xinwen_id}}">删除</a>
                    <a href="xinwen_detail/{{$v->xinwen_id}}">查看详情</a>


                </td>
            </tr>
        @endforeach
    </table>
{{ $data->links() }}
