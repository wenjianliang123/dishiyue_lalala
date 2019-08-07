@extends('layout.common')
@section('content')
    <a href="{{url('wechat/get_user_list')}}">刷新用户列表</a>
    <table border="1" align="center">
        <tr>
            <td>ID</td>
            <td>是否关注</td>
            <td>openid</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v->id}}</td>
                <td>@if($v->subscribe == 1)已关注@else未关注@endif</td>
                <td>{{$v->open_id}}</td>
                <td>
                    <a href="{{url('/wechat/user_detail',['id'=>$v->id])}}">查看详情</a>
                </td>
            </tr>
        @endforeach
    </table>
@endsection