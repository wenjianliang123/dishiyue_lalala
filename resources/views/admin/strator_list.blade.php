<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="/jquery-3.3.1.js"></script>
</head>
<body>
    <form action="{{url('stratot_list_add')}}" method="post">
    @csrf
        <select class="bb">
            <option value="单选">单选</option>
            <option value="多选">多选</option>
            <option value="判断">判断</option>
        </select>
        <div class="radio">
           单选问题: <input type="text" name="danxuan_wenti"><br />
        <input type="radio" name="danxuan_xuanxiang" value="A">A <input type="text" name="danxuan_daan_A"><br>
        <input type="radio" name="danxuan_xuanxiang" value="B">B <input type="text" name="danxuan_daan_B"><br>
        <input type="radio" name="danxuan_xuanxiang" value="C">C <input type="text" name="danxuan_daan_C"><br>
        <input type="radio" name="danxuan_xuanxiang" value="D">D <input type="text" name="danxuan_daan_D"><br>
        <input type="submit" value="添加">
    </div>
     </form>
     <form action="{{url('strator_list_acc')}}" method="post">
       @csrf
        <div class="checkbox">
            多选问题: <input type="text" name="duoxuan_wenti"><br />
            <input type="checkbox" name="duoxuan_xuanxiang[]" value="A">A<input type="text" name="duoxuan_daan_A"><br>
            <input type="checkbox" name="duoxuan_xuanxiang[]" value="B">B<input type="text" name="duoxuan_daan_B"><br>
            <input type="checkbox" name="duoxuan_xuanxiang[]" value="C">C<input type="text" name="duoxuan_daan_C"><br>
            <input type="checkbox" name="duoxuan_xuanxiang[]" value="D">D<input type="text" name="duoxuan_daan_D"><br>
            <input type="submit" value="添加">
        </div>
    </form>
    <form action="{{url('strator_list_abb')}}" method="post">
    @csrf
        <div class="cc">
            <input type="text" name="aaaa"><br>
            <input type="radio" name="bbbb" value="正确">正确
            <input type="radio" name="bbbb" value="错误">错误<br>
            <input type="submit" value="添加">
        </div>
    </form>
    
</body>
</html>
<script>
    $(function(){
        $('.radio').hide();
        $('.checkbox').hide();
        $('.cc').hide();
        $('.bb').click(function(){
            var name=$(this).val();
            if(name=='单选'){
                $('.radio').show();
                $('.checkbox').hide();
                $('.cc').hide();
            };
            if(name=='多选'){
                $('.checkbox').show();
                $('.radio').hide();
                $('.cc').hide();
            };
            if(name=='判断'){
                $('.cc').show();
                $('.checkbox').hide();
                $('.radio').hide();
            }
        });
    });
</script>