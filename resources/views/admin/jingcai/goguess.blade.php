<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加结果</title>
</head>
<body>
        <form action="result" method="post">
        @csrf
                <h3>竞猜结果</h3>
                <input type="hidden" name="id" value="{{ $re->id }}">
                {{ $re->team }}VS{{ $re->teams }}<br>
                <input type='radio' name="result" value="胜">胜
                <input type='radio' name="result" value="负">负
                <input type='radio' name="result" value="平">平
                <br>
                <input type="submit" value="提交">
        </form>
</body>
</html>