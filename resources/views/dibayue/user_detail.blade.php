@extends('layout.common')
@section('content')

    <table border="1" align="center">
        <tr>
            <td align="center">用户ID</td>
            <td align="center">用户昵称</td>
            <td align="center">用户头像</td>
            <td align="center">是否关注</td>
            <td align="center">性别</td>
            <td align="center">城市</td>
            <td align="center">openid</td>
        </tr>

            <tr>
                <td align="center">{{$id}}</td>
                <td align="center">{{$data['nickname']}}</td>
                <td align="center"><img src="{{asset($data['headimgurl'])}}" width="100" alt=""></td>
                <td align="center">@if($data['subscribe'] == 1)已关注@else关注@endif</td>
                <td align="center">@if($data['sex'] === 0)未设置@elseif($data['sex']==1)男@elseif($data['sex']==2)女@endif</td>
                <td align="center">{{$data['country']}}{{$data['province']}}{{$data['city']}}</td>
                <td align="center">{{$data['openid']}}</td>
            </tr>

    </table>
@endsection