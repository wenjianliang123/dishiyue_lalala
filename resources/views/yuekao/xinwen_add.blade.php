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
<form action="{{url("yuekao/xinwen/xinwen_do_add")}}" method="post" enctype="multipart/form-data">
    @csrf
    <table border="1" align="center">
        <tr>
            <td>新闻标题</td>
            <td><input type="text" name="xinwen_name" id=""></td>
        </tr>
        <tr>
            <td>新闻图片</td>
            <td><input type="file" name="xinwen_picture" id=""></td>
        </tr>
        <tr>
            <td>作者</td>
            <td><input type="text" name="xinwen_zuozhe" id=""></td>
        </tr>
        <tr>
            <td>新闻详情</td>
            <td><textarea name="xinwen_detail" id="" cols="30" rows="10"></textarea></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="" id="" value="添加新闻">
            </td>
        </tr>
    </table>
</form>

</body>
</html>
