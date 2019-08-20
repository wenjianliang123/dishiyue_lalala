<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>收费详情</title>
</head>
<body>
    <table border=1>
        <tr>
            <td>尊敬的{{$data->chepaihao}}车主</td>
        </tr>
        <tr>
            <td>停车：{{$hours}}小时{{$minute}}分钟</td>
        </tr>
        <tr>
            <td>收费：{{$data->money}}元</td>
        </tr>
    </table>
</body>
</html>