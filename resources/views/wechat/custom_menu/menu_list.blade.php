<table border="1">
    <tr>
        <td>菜单ID</td>
        <td>菜单类型</td>
        <td>事件类型</td>
        <td>第一级菜单</td>
        <td>第二级菜单</td>
        <td>等级</td>
        <td>菜单备注</td>
    </tr>
    @for($i=0;$i<=(count($data))-1;$i++)
        <tr>
            <td>{{$data[$i]['menu_id']}}</td>
            <td>{{$data[$i]['menu_type']}}</td>
            <td>{{$data[$i]['event_type']}}</td>
            <td>{{$data[$i]['first_menu_name']}}</td>
            <td>{{$data[$i]['second_menu_name']}}</td>
            <td>{{$data[$i]['menu_str']}}</td>
            <td>{{$data[$i]['menu_remark']}}</td>
        </tr>
    @endfor
</table>

