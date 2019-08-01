<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>竞猜结果</title>
</head>
<body>
        <h2>竞猜结果</h2>
        <h3>对阵结果：{{ $user->team }}{{ $user->result }}{{ $user->teams }}</h3>
        <h3>您的竞猜：{{ $user->team }}{{ $user->quiz }}{{ $user->teams }}</h3>
        <h3>结果：
        @if($user->result == $user->quiz)
            恭喜您，竞猜成功
        @else
            很遗憾，您猜错了
        @endif        
        </h3>
</body>
</html>