@extends('layout.index')
@section('title', '商品列表')
@section('content')
<!-- shop single -->
<div class="pages section">
    <div class="container">
        <div class="shop-single">
            <img src="{{asset($goods_detail->goods_picture)}}" alt="">
            <h5>{{$goods_detail->goods_name}}</h5>
            <div class="price">
                单价：$<b class="danjia">{{$goods_detail->goods_price}}</b>
                <div>小计：$<b class="xiaoji"></b></div>
                <span>$28</span>
                <input type="hidden" class="goods_id" goods_id="{{$goods_detail->goods_id}}">
            </div>
            <div class="">
                <button id="jian">-</button>
                <input type="text" style="width:50px" value="1" id="shuliang" goods_number="{{$goods_detail->goods_number}}">
                <button id="jia">+</button></div>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam eaque in non delectus, error iste veniam commodi mollitia, officia possimus, repellendus maiores doloribus provident. Itaque, ab perferendis nemo tempore! Accusamus</p>
            <button type="button" class="btn button-default buy_cart">ADD TO CART</button>
        </div>
        <div class="review">
            <h5>1 reviews</h5>
            <div class="review-details">
                <div class="row">
                    <div class="col s3">
                        <img src="img/user-comment.jpg" alt="" class="responsive-img">
                    </div>
                    <div class="col s9">
                        <div class="review-title">
                            <span><strong>John Doe</strong> | Juni 5, 2016 at 9:24 am | <a href="">Reply</a></span>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis accusantium corrupti asperiores et praesentium dolore.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="review-form">
            <div class="review-head">
                <h5>Post Review in Below</h5>
                <p>Lorem ipsum dolor sit amet consectetur*</p>
            </div>
            <div class="row">
                <form class="col s12 form-details">
                    <div class="input-field">
                        <input type="text" required class="validate" placeholder="NAME">
                    </div>
                    <div class="input-field">
                        <input type="email" class="validate" placeholder="EMAIL" required>
                    </div>
                    <div class="input-field">
                        <input type="text" class="validate" placeholder="SUBJECT" required>
                    </div>
                    <div class="input-field">
                        <textarea name="textarea-message" id="textarea1" cols="30" rows="10" class="materialize-textarea" class="validate" placeholder="YOUR REVIEW"></textarea>
                    </div>
                    <div class="form-button">
                        <div class="btn button-default">POST REVIEW</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end shop single -->
<script src="{{asset('/index/js/jquery.min.js')}}"></script>
<script type="text/javascript">

    $(function () {
        var xiaoji =$('.xiaoji').text(newprice());
        // 点击加号 数量加一
        $('#jia').click(function(){
            var shuliang =  parseInt($('#shuliang').val());
            var num = shuliang+1;
            var kucun = $('#shuliang').attr('goods_number');
            var xiaoji =$('.xiaoji').text(newprice());
            $('#shuliang').val(num);
            if (shuliang >= kucun) {
                $('#shuliang').val(kucun);
            }
            $('.xiaoji').text(newprice());

        });
        // 点击减号 数量减一
        $('#jian').click(function(){
            var shuliang =  parseInt($('#shuliang').val());
            var num = shuliang-1;
            var kucun = $('#shuliang').attr('goods_number');
            var xiaoji =$('.xiaoji').text(newprice());
            $('#shuliang').val(num);
            if (shuliang <= 1) {
                $('#shuliang').val(1);
            }
            $('.xiaoji').text(newprice());

        });
        function newprice()
        {
            var shuliang =  parseInt($('#shuliang').val());
            var danjia =Number($('.danjia').text());
            var xiaoji=parseInt(shuliang*danjia);
            return xiaoji;
        }

        $(document).on('click','.buy_cart',function(){
            var goods_id=$('.goods_id').attr('goods_id');
            var goods_id=parseInt(goods_id);
//            alert(goods_id);
            var shuliang =  parseInt($('#shuliang').val());
            var shuliang=parseInt(shuliang);
//            alert(shuliang);

            if (goods_id==''||shuliang=='') {
                alert('参数不能为空啊啊啊');
            }




            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{url('/index/buy_cart')}}",
                type:'post',
                data:{goods_id:goods_id,shuliang:shuliang},
                success:function(res){
//                    console.log(res);
//                    alert(res['font']);

                    var msg = JSON.parse(res);
                    if(msg.code==1) {
                        alert(msg.font);
                        var result =confirm('加入购物车成功，小伙伴要不要去购物车');
                        if(result){
                            location.href="{{url('/index/buy_cart_1')}}";
//                            alert("这个时候该去处理并显示视图了");
//                            alert(msg.font);
                        }else{
                            history.go(0);
                        }

                    }else{
                        alert(msg.font);
                        location.href="{{url('/index/login/login')}}";
                    }
                }
            })
            // alert(goods_id);

        })
    });
</script>

@endsection