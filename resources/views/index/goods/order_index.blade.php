@extends('layout.index')
@section('content')
    <style>
        [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
            position: inherit;
            left: -9999px;
            opacity: 1;
        }
    </style>

    <!-- cart -->

    <div class="cart section">
        <div class="container">
            <div class="pages-head">
                <h3>确认订单</h3>
            </div>
            <td width="100%" colspan="4">
                <a href="javascript:;"><input type="checkbox" name="1" id="allbox" /> 全选</a>
            </td>
            @foreach($data1 as $v)

                <div class="content">
                    <div class="cart-1">
                        <div class="row">

                            <div class="col s5">
                                <td width="4%">
                                    <input type="checkbox" class="box" shuliang="{{$v['buy_number']}}" newprice="{{$v['sub_total']}}" goods_id="{{$v['goods_id']}}"/>
                                </td>
                                <h5>IMAGE</h5>

                                <input type="hidden" name="goods_id" class="goods_id" goods_id="{{$v['goods_id']}}">
                            </div>
                            <div class="col s7">
                                <img src="{{asset($v['goods_picture'])}}" alt="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>Name</h5>
                            </div>
                            <div class="col s7">
                                <h5><a href="">{{$v['goods_name']}}</a></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>Quantity</h5>
                            </div>
                            <div class="col s7">
                                <input id="shuliang" value="{{$v['buy_number']}}" style="width: 50px" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>单价</h5>
                            </div>
                            <div class="col s7">
                                <h5><a href="" class="danjia">{{$v['goods_price']}}</a></h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s5">
                                <h5>小计</h5>
                            </div>
                            <div class="col s7">
                                <h5 class="xiaoji">${{$v['buy_number']*$v['goods_price']}}</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s5">
                                <h5>Action</h5>
                            </div>
                            <div class="col s7">
                                <h5><i class="fa fa-trash"></i></h5>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                </div>
            @endforeach
            <div class="total">
                {{--@foreach($data1 as $vv)--}}
                {{--<div class="row">--}}
                {{--<div class="col s7">--}}
                {{--<h5>{{$vv['goods_name']}}</h5>--}}
                {{--</div>--}}
                {{--<div class="col s5">--}}
                {{--<h5 class="">${{$vv['buy_number']*$vv['goods_price']}}</h5>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--@endforeach--}}

                <div class="row">
                    <div class="col s7">
                        <h6>Total</h6>
                    </div>
                    <div class="col s5">
                        $<b class="total_price">0</b>
                    </div>
                </div>
            </div>
            <button class="btn button-default order">确认支付</button>
        </div>
    </div>

    <script src="{{asset('/index/js/jquery.min.js')}}"></script>
    <script type="text/javascript">

        $(function () {

            // 给当前复选框选中
            function boxChecked(_this){
                _this.parents("tr").find("input[class='box']").prop("checked",true);
                // 调用获取商品总价方法
            }


            //全选
            $("#allbox").click(function(){
                var status = $(this).prop('checked');
                $('.box').prop('checked',status);

                // 调用获取商品总价方法
                countTotal();
            });


            //复选框选中
            $(document).on('click','.box',function(){
                // 调用获取商品总价方法
                countTotal();
            });


            function countTotal(){
                // 获取所有选中的复选框 对应的商品id
                var _box = $('.box');
                var total_price=0;
                _box.each(function(index){
                    if ($(this).prop('checked') == true) {
                        total_price+=parseInt($(this).attr('newprice'));
                    }
                });
//
                $('.total_price').text(total_price);
            }

            $(document).on('click','.order',function(){

                //待测试
                /*var goods_id='';
                 $(".goods_id").each(function(){
                 if($(this).parents('.car_tr').find('.box').prop('checked')){
                 goods_id +=','+$(this).attr('goods_id');
                 }

                 });
                 goods_id=goods_id.substr(1,goods_id.length);*/


                //获取选中的复选框的商品id
                var goods_id=[];
                $('.box:checked').each(function(){
                    var goods_id_old=$(this).attr('goods_id');
                    goods_id.push(goods_id_old);
                })
                var _id=goods_id.join(',');
                if (_id==''){
                    alert('请至少选择一件商品进行结算');
                    return false;
                }

                //获取总价
                var total_price=$(".total_price").text();
//            alert('这是总价'+total_price);


//            alert(shuliang_new);return;
//return;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{url('/zhifubao/pay')}}",
                    type:'post',
                    data:{goods_id:_id,total_price:total_price},
                    success:function(res){
                        console.log(res);

                        {{--var msg = JSON.parse(res);--}}

                        {{--if(msg.code==1) {--}}

                            {{--var result =confirm('加入订单成功,小伙伴要不要去结个账!!!');--}}
                            {{--if(result){--}}
                                {{--location.href="{{url('/index/do_pay')}}";--}}
                            {{--}else{--}}
                                {{--history.go(0);--}}
                            {{--}--}}

                        {{--}else{--}}
                            {{--alert(msg.font);--}}
                            {{--location.href="{{url('/index/login/login')}}";--}}
                        {{--}--}}
                    }
                })
//                 alert(goods_id);

            })

        });

    </script>





    <!-- end cart -->
@endsection



