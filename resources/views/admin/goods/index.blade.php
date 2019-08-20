@extends('layout.common')
@section('content')
<form action="{{url('/admin/goods/index')}}" method="get">
    货物名称：<input type="text" name="goods_name" id="" value="{{$goods_name}}">
    <input type="submit" value="搜索">
</form>
<table border="1">
    <tr>
        <td>ID</td>
        <td>货物名称</td>
        <td>货物图片</td>
        <td>库存</td>
        <td>操作</td>
    </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v->goods_id}}</td>
                <td>{{$v->goods_name}}</td>
                <td><img src="{{asset($v->goods_picture)}}" width="100" alt=""></td>
                <td>{{$v->goods_number}}</td>
                <td>
                    <a href="{{url('/admin/goods/edi+t',['goods_id'=>$v->goods_id])}}">修改</a>
                    <a href="del/{{$v->goods_id}}">删除</a>

                </td>
            </tr>
        @endforeach
</table>
{{ $data->appends(['goods_name' => $goods_name])->links() }}
    @endsection