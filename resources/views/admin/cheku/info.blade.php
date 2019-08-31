<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>数据统计</title>
</head>
<body>
    <table border=1>
        <tr>
            <th>总车位</th>
            <td>{{$data->sum}}</td>
        </tr>
        <tr>
            <th>剩余车位</th>
            <td>{{$data->shengyu}}</td>
        </tr>
        <tr>
            <th>进入数量</th>
            <td>{{$data->num}}</td>
        </tr>
        <tr>
            <th>收费总计</th>
            <td>{{$data->money}}</td>
        </tr>
    </table>
</body>
</html>