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
<form action="{{url('/jiuyue/student/student_list')}}" method="get">
    姓名：   <input type="text" name="student_name" value="{{$student_name}}">
    性别:
        男<input type="radio" name="student_sex" id="" value="1" >
        女<input type="radio" name="student_sex" id="" value="2" >
    <input type="submit" value="搜索">
</form>
<table border="1">

    <tr>
        <td>ID</td>
        <td>学生姓名</td>
        <td>学生性别</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td>{{$v->student_id}}</td>
            <td>{{$v->student_name}}</td>
            <td>@if($v->student_sex==1)男 @elseif($v->student_sex==2)女@endif</td>
            <td>
                <a href="{{url('/jiuyue/student/student_edit',['student_id'=>$v->student_id])}}">修改</a>
                <a href="{{url('jiuyue/student/student_delete',['student_id'=>$v->student_id])}}">删除</a>
                {{--<a href="del/{{$v->student_id}}">删除</a>--}}
            </td>
        </tr>
    @endforeach
</table>
{{ $data->appends(['student_name' => $student_name,'student_sex'=>$student_sex])->links() }}
</body>
</html>
