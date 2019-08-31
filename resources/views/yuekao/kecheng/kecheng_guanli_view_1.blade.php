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
<form action="{{url("/kecheng/kecheng_guanli_view_do_add")}}" method="post">
    @csrf
    <table border="1" align="center">
        <tr>
            <td colspan="2" align="center">
                第一节课：<select name="kecheng_1"  id="">
                    <option value="PHP">PHP</option>
                    <option value="数学">数学</option>
                    <option value="语文">语文</option>
                    <option value="英语">英语</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                第二节课：<select name="kecheng_2"  id="">
                    <option value="数学">数学</option>
                    <option value="PHP">PHP</option>
                    <option value="语文">语文</option>
                    <option value="英语">英语</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                第三节课：<select name="kecheng_3" id="">
                    <option value="语文">语文</option>
                    <option value="PHP">PHP</option>
                    <option value="数学">数学</option>
                    <option value="英语">英语</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                第四节课：<select name="kecheng_4"  id="">
                    <option value="英语">英语</option>
                    <option value="PHP">PHP</option>
                    <option value="数学">数学</option>
                    <option value="语文">语文</option>

                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="" id="" value="提交">
            </td>
        </tr>
    </table>
</form>

</body>
</html>
