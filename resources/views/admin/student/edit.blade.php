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
<form action="{{url("admin/student/update")}}">
    @foreach($data1 as $v)
    <input type="hidden" name="student_id" value="{{$v->student_id}}">
    @csrf
    <table border="1" align="center">
        <tr>
            <td>学生姓名</td>
            <td><input type="text" name="student_name" value="{{$v->student_name}}"></td>
        </tr>
        <tr>
            <td>学生性别</td>
            <td>
                <input type="radio" name="student_sex" id="" value="1" @if($v->student_sex == 1)checked @endif>男
                <input type="radio" name="student_sex" id="" value="2" @if($v->student_sex == 2)checked @endif>女
            </td>
        </tr>
        <tr>
            <td>学生班级</td>
            <td>
                <select name="student_class" id="">
                    @foreach($class_info as $vv)
                        <option value="{{$vv->class_id}}" @if($v->student_class==$vv->class_id) selected @endif>{{$vv->class_name}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="修改"></td>
        </tr>
    </table>
        @endforeach
</form>
</body>
</html>
@endsection