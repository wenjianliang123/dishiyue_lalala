@extends('layout.common')
@section('content')
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
<form action="{{url('admin/student/index')}}" method="get">
    姓名：   <input type="text" name="student_name" value="{{$student_name}}">
    学生班级：<select name="student_class" id="">
                        <option value="" disabled selected style="display: none">请选择班级</option>
                    @foreach($class_info as $vv)
                        <option value="{{$vv->class_id}}" @if($student_class==$vv->class_id)selected @endif>{{$vv->class_name}}</option>

        @endforeach
                </select>
            <input type="submit" value="搜索">
</form>
    <table border="1">
        登陆后用户名为{{Session::get('user_name')}}
        <tr>
            <td>ID</td>
            <td>学生姓名</td>
            <td>学生性别</td>
            <td>学生班级</td>
            <td>操作</td>
        </tr>
       @foreach($data as $v)
            <tr>
            <td>{{$v->student_id}}</td>
            <td>{{$v->student_name}}</td>
            <td>@if($v->student_sex==1)男 @elseif($v->student_sex==2)女@endif</td>
            <td>{{$v->class_name}}</td>
            <td>{{date("Y-m-d H:i:s",$v->create_time)}}</td>
                <td>
                    <a href="{{url('/admin/student/edit',['student_id'=>$v->student_id])}}">修改</a>
                    <a href="{{url('/admin/student/delete',['student_id'=>$v->student_id])}}">删除</a>
                    {{--<a href="del/{{$v->student_id}}">删除</a>--}}
                </td>
            </tr>
        @endforeach
    </table>
{{ $data->appends(['student_name' => $student_name,'student_class'=>$student_class])->links() }}
</body>
</html>
@endsection