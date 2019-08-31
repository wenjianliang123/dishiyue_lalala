<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加车位信息</title>
</head>
<body>
    <form action="{{url('admin/cheku/doaddcar')}}" method="post">
        @csrf
        <table border=1>
            <tr>
                <th>添加车位数</th>
                <td><input type="text" name="sum"></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" value="添加车位"></td>
            </tr>
        </table>
    </form>
</body>
</html>