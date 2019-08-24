<form action="{{url('biaobai/push_biaobai')}}" method="post">
    @csrf
    <input type="hidden" name="open_id" value="{{$open_id}}">
    表白内容：<textarea name="biaobai_content" id="" cols="30" rows="10"></textarea>
    <br />
    表白类型：
    表白<input type="radio" name="biaobai_leixing" id="" value="1" checked>
    匿名表白<input type="radio" name="biaobai_leixing" id="" value="2">
    <hr/>
    <input type="submit" value="发送表白">
</form>

