<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>车辆入库</title>
</head>
<body>
<h1>车辆入库</h1>
    <form action="{{url('admin/cheku/docarin')}}" method="post">
        @csrf
        <table border=1>
            <tr>
                <th>车牌号</th>
                <td><input type="text" name="chepaihao"></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" value="车辆进入"></td>
            </tr>
        </table>
    </form>
</body>
</html>