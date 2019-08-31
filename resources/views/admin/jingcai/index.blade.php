<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>竞猜页面</title>

</head>
<body>
        <h3>竞猜列表</h3>
        @foreach ($re as $user) 
        {{ $user->team }}VS{{ $user->teams }}
        @if($user->time > date('Y-m-d',time()))
            <a href="guess?id={{ $user->id }}">竞猜</a>|||||<a href="goguess?id={{ $user->id }}">添加竞猜结果</a>
        @else
            <a href="results?id={{ $user->id }}">查看结果</a>
        @endif
        <br>

        @endforeach
</body>
</html>