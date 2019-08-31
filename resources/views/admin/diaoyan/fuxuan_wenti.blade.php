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
<form action="{{url('admin/diaoyan/fuxuan_answer_do_add')}}" method="post">
    @csrf
    <table align="center">
        <tr>
            <td>{{$diaoyan_wenti}}</td>
        </tr>

        <input type="hidden" name="diaoyan_wenti_id" value="{{$info}}">
        <input type="hidden" name="diaoyan_xiangmu_id" value="{{$diaoyan_xiangmu_id}}">
        <tr>
            <td><input type="checkbox" name="answer_xuanxiang_fuxuan[]" value="1">A:<input type="text" name="answer_a"></td>
        </tr>
        <tr>
            <td><input type="checkbox" name="answer_xuanxiang_fuxuan[]" value="2">B:<input type="text" name="answer_b"></td>
        </tr>
        <tr>
            <td><input type="checkbox" name="answer_xuanxiang_fuxuan[]" value="3">C:<input type="text" name="answer_c"></td>
        </tr>
        <tr>
            <td><input type="checkbox" name="answer_xuanxiang_fuxuan[]" value="4">D:<input type="text" name="answer_d"></td>
        </tr>
        <tr>
            <td><input type="submit" value="提交"></td>
        </tr>
    </table>
</form>
</body>
</html>