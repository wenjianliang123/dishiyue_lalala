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
    <form action="{{url("admin/student/do_add")}}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table border="1" align="center">
            <tr>
                <td>学生姓名</td>
                <td><input type="text" name="student_name"></td>
            </tr>
            <tr>
                <td>学生性别</td>
                <td>
                    <input type="radio" name="student_sex" value="1" checked>男
                    <input type="radio" name="student_sex" value="2">女
                </td>
            </tr>
            <tr>
                <td>学生班级</td>
                <td>
                    <select name="student_class" id="">
                        @foreach($data as $v)
                        <option value="{{$v->class_id}}">{{$v->class_name}}</option>
                            @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="添加"></td>
            </tr>
        </table>
    </form>
</body>
</html>
@endsection