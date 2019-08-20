<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/js/jquery-3.3.1.js"></script>
    <title>添加竞猜</title>
</head>
<body>
                <h3>添加竞猜球队</h3>
                <input type="text" name="" id="a">VS<input type="text" name="" id="b"><br>
                <h2>结束竞猜时间</h2><input type="datetime-local" name="" id="c"><br>
                <button>添加</button>
</body>
<script>
        $('button').click(function(){
            var team = $('#a').val();
            var teams = $('#b').val();
            var time = $('#c').val();
            //alert(team);
            if(team==''||teams==''||time==''){
                alert('必选项不能为空');
            }else if(team==teams){
                alert("两只球队不可重复")
            }else{
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    'url':'doadd'
                    ,'data':{team:team,teams:teams,time:time}
                    ,'type':'post'
                    ,'dataType':'json'
                    ,success:function(res){
                        if(res.msg=='1'){
                            location.href="index";
                        }
                    }
                })
            }
        })
</script>
</html>