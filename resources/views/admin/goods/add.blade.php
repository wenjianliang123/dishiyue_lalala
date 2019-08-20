@extends('layout.common')
@section('content')
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
<form action="{{url("admin/goods/do_add")}}" method="post" enctype="multipart/form-data">
    @csrf
    <table border="1" align="center">
        <tr>
            <td>商品名称</td>
            <td><input type="text" name="goods_name" id=""></td>
        </tr>
        <tr>
            <td>商品图片</td>
            <td><input type="file" name="goods_picture" id=""></td>
        </tr>
        <tr>
            <td>商品价格</td>
            <td><input type="number" name="goods_price" id=""></td>
        </tr>
        <tr>
            <td>商品库存</td>
            <td><input type="number" name="goods_number" id=""></td>
        </tr>
        <tr>
            <td>是否最新</td>
            <td>
                <input type="radio" name="is_new" id="" value="1">是
                <input type="radio" name="is_new" id="" value="2">否
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="" id="" value="添加商品">
            </td>
        </tr>
    </table>
</form>

</body>
</html>
@endsection