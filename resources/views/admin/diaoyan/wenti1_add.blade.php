<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form action="{{url('admin/diaoyan/wenti1_do_add')}}" method="post">
    @csrf
    <table border="1" align="center">

            <tr>
                <td>调研问题：<input type="text" name="diaoyan_wenti"></td>
                <input type="hidden" name="diaoyan_xiangmu_id" value="{{$info}}" >
            </tr>
            <tr>
                <td>
                    问题选项：
                    <input type="radio" name="diaoyan_xuanxiang" value="1">单选
                    <input type="radio" name="diaoyan_xuanxiang" value="2">复选
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <input type="submit" value="添加问题"/>
                </td>
            </tr>

    </table>
    </form>
</body>
</html>