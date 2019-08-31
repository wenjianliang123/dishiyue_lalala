<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{url('strator_list_do')}}" method="post">
    @csrf
        <tr>
           <td>试卷名称：</td>
           <td><input type="text" name="title"></td><br>
        </tr>
        <tr>
           <td></td>
           <td><input type="submit" value="添加试卷"></td>
        </tr>
    </form>
</body>
</html>