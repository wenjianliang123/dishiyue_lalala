@extends('layout.common')
@section('content')

    <table border="1" align="center">
        <tr align="center">
            <td>用户ID</td>
            <td>用户昵称</td>
            <td>用户头像</td>
            <td>是否关注</td>
            <td>性别</td>
            <td>城市</td>
            <td>openid</td>
        </tr>

            <tr align="center">
                <td>{{$id}}</td>
                <td>{{$data['nickname']}}</td>
                <td><img src="{{asset($data['headimgurl'])}}" width="100" alt=""></td>
                <td>@if($data['subscribe'] == 1)已关注@else关注@endif</td>
                <td>@if($data['sex'] === 0)未设置@elseif($data['sex']==1)男@elseif($data['sex']==2)女@endif</td>
                <td>{{$data['country']}}{{$data['province']}}{{$data['city']}}</td>
                <td>{{$data['openid']}}</td>
            </tr>

    </table>
@endsection