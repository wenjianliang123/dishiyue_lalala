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
<form action="{{url('jiuyue/student/student_do_add')}}" method="post">
    @csrf
    <center>
        <table>
            <tr>
                <td>学生姓名</td>
                <td><input type="text" name="student_name"></td>
            </tr>
            <tr>
                <td>学生性别</td>
                <td>
                    男<input type="radio" name="student_sex" id="" value="1" checked>
                    女<input type="radio" name="student_sex" id="" value="2">
                </td>
            </tr>

            <tr>
                <td></td>
                <td colspan="2">
                    <input type="submit" value="添加学生">
                </td>
            </tr>
        </table>
    </center>
</form>
</body>
</html>