




<table border="1">
    <tr>
        <td>发送者：</td>
        <td>接收人：</td>
        <td>留言内容:</td>

    </tr>
    @for($i=0;$i<=(count($data))-1;$i++)
        <tr>
            <td>{{$data[$i]['push_user']}}<br /></td>
            <td>{{$data[$i]['user_name']}}<hr /></td>
            <td>
                {{$data[$i]['liuyan_content']}}
            </td>
        </tr>
    @endfor
</table>