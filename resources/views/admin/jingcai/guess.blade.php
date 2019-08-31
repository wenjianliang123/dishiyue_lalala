<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>竞猜页面</title>
</head>
<body>
        <form action="q" method="post">
        @csrf
                <h3>我要竞猜</h3>
                <input type="hidden" name="guess" value="{{ $re->id }}">
                {{ $re->team }}VS{{ $re->teams }}<br>
                <input type='radio' name="quiz" value="胜">胜
                <input type='radio' name="quiz" value="负">负
                <input type='radio' name="quiz" value="平">平
                <br>
                <input type="submit" value="提交">
        </form>
</body>
</html>