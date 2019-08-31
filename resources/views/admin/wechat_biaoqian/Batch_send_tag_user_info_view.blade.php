<!DOCTYPE html>
<html>
<head>
    <title>根据标签群发消息</title>
</head>
<body>
<center>
    <form action="{{url('wechat/biaoqian/Batch_send_tag_user_info')}}" method="post">
        @csrf
        消息类型：文本消息<hr />
        <input type="hidden" name="tag_id" value="{{$tag_id}}">
        消息内容：<textarea name="content" id="" cols="30" rows="10"></textarea>
        <br/><br/>
        <input type="submit" value="提交">
    </form>

</center>
</body>
</html>