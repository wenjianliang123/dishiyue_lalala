@extends('layout.common')
@section('content')
    <form action="{{url('wechat/biaoqian/Batch_tag_users')}}" method="post">
        @csrf
        <a href="{{url('wechat/get_user_list')}}">刷新用户列表</a>
        <input type="hidden" name="tag_id" value="{{$tag_id}}">
        <table border="1" align="center">
            <tr><td colspan="5" align="center"><input type="submit" value="批量给粉丝打标签"></tr></td>
            <tr>
                <td>全选 <input type="checkbox"  class="all"></td>
                <td>ID</td>
                <td>是否关注测试号</td>
                <td>openid</td>
                <td>操作</td>
            </tr>
            @foreach($data as $v)
                <tr>
                    <td><input type="checkbox" name="openid_list[]" class="box" value="{{$v->open_id}}"></td>
                    <td>{{$v->id}}</td>
                    <td>@if($v->subscribe == 1)已关注@else未关注@endif</td>
                    <td>{{$v->open_id}}</td>
                    <td>
                        <a href="{{url('/wechat/user_detail',['id'=>$v->id])}}">查看详情</a>
                        <a href="{{url('wechat/biaoqian/get_user_tag',['openid'=>$v->open_id])}}">获取该用户标签</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </form>
    <script src="/jquery-3.3.1.js"></script>
    <script>
        $('.all').click(function(){
            var status = $(this).prop('checked');
            $('.box').prop('checked',status);
        });

    </script>
    
@endsection