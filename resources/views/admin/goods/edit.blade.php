@extends('layout.common')
@section('content')
<form action="{{url('/admin/goods/update')}}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="goods_id" value="{{$data->goods_id}}">
    @csrf
    <table border="1">
        <tr>
            <td>货物名称111111</td>
            <td><input type="text" name="goods_name" id="" value="{{$data->goods_name}}"></td>
        </tr>
        <tr>
            <td>货物图片</td>
            <td><input type="file" name="goods_picture" id="" value="{{$data->goods_picture}}"></td>
        </tr>
        <tr>
            <td>库存</td>
            <td><input type="text" name="goods_number" id="" value="{{$data->goods_number}}"></td>
        </tr>
        <tr>
            <td>商品价格</td>
            <td><input type="text" name="goods_price" id="" value="{{$data->goods_price}}"></td>
        </tr>
        <tr>
            <td>是否最新</td>
            <td>
                <input type="radio" name="is_new" id="" value="1" @if($data->is_new == 1)checked @endif>是
                <input type="radio" name="is_new" id="" value="2" @if($data->is_new == 2)checked @endif>否
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" name="" value="提交" id=""></td>
        </tr>
    </table>

</form>
    @endsection