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

    <form action="{{url('admin/diaoyan/do_add')}}" method="post">
        <table border="1" align="center">
        @csrf
        <tr>
            <td>调研项目：</td>
            <td><input type="text" name="diaoyan_name"></td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <input type="submit" value="添加调研" >
            </td>
        </tr>

        </table>
    </form>


</body>
</html>