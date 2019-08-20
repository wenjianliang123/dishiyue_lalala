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
<form action="{{url('zhoukao/liuyan/push_liuyan')}}" method="post">
    @csrf
    <input type="hidden" name="user_id" value="{{$user_id}}">
    留言内容：<textarea name="liuyan_content" id="" cols="30" rows="10"></textarea>
    <input type="submit" value="发送留言">
</form>
</body>
</html>