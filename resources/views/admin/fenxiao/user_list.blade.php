<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>微信分销用户列表</title>
</head>
<body>
<center>
    <button class="getLocation">查看地址</button>
    <table border="1">
        <tr>
            <td>用户user_id</td>
            <td>用户专属推广码</td>
            <td>用户专属二维码</td>
            <td>操作</td>
        </tr>
        @foreach($data as $v)
            <tr>
                <td>{{$v->id}}</td>
                <td>{{$v->agent_code}}</td>
                <td>
                    <img width="150px" src="{{$v->qrcode_url}}"></img>
                </td>
                <td>
                    <a href="{{url('admin/fenxiao/create_qrcode')}}?id={{$v->id}}">生成用户专属二维码</a> |
                    <a href="{{url('admin/fenxiao/agent_list')}}?id={{$v->id}}">用户推广用户列表</a>
                    <button class="fenxiang_1">分享给朋友、分享到QQ</button>
                    <button class="fenxiang_2">分享到朋友圈、分享到QQ空间</button>
                </td>
            </tr>
        @endforeach
    </table>

</center>
</body>
</html>
<script src="/jquery-3.3.1.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /**
         * 通过config接口注入权限验证配置
         * 所有需要使用JS-SDK的页面必须先注入配置信息，
         * 否则将无法调用（同一个url仅需调用一次，
         * 对于变化url的SPA的web app可在每次url变化时进行调用,
         * 目前Android微信客户端不支持pushState的H5新特性，
         * 所以使用pushState来实现web app的页面会导致签名失败，
         * 此问题会在Android6.2中修复）。
         */
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "{{$jsconfig['appid']}}", // 必填，公众号的唯一标识
            timestamp: '{{$jsconfig['timestamp']}}', // 必填，生成签名的时间戳
            nonceStr: "{{$jsconfig['noncestr']}}", // 必填，生成签名的随机串
            signature: "{{$jsconfig['sign']}}",// 必填，签名
            jsApiList: ['chooseImage', 'uploadImage', 'getLocalImgData', 'startRecord', 'onMenuShareAppMessage', 'getLocation', 'openLocation'] // 必填，需要使用的JS接口列表
        });
        /**
         * 自定义“分享给朋友”及“分享到QQ”按钮的分享内容（1.4.0）
         */

        wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
            $('.fenxiang_1').click(function () {
                wx.onMenuShareAppMessage({
                    title: '商品列表', // 分享标题
                    desc: '商品列表1', // 分享描述
                    link: 'http://www.wenjianliang.top/', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://thirdwx.qlogo.cn/mmopen/ajNVdqHZLLCDtYhbXkiaY8XPCZy1xmPgfzjMbnG7h3ibhJia46qjLBrVl1mT185xWabBVqHtsTdLslKkZhSktYicbw/132', // 分享图标
                    type: '', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success: function () {
                        // 用户点击了分享后执行的回调函数
                    }
                });
            });

        });
        /**
         * 自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容（1.4.0）
         */

        wx.ready(function () {      //需在用户可能点击分享按钮前就先调用
            $('.fenxiang_2').click(function () {
                wx.updateTimelineShareData({
                    title: '自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容', // 分享标题
                    link: '2', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: '2', // 分享图标
                    success: function () {
                        // 设置成功
                    }
                })
            });

        });

//        获取地理位置接口
            wx.ready(function () {
                $('.getLocation').click(function () {
                    wx.getLocation({
                        type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                        success: function (res) {
                            var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                            var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                            var speed = res.speed; // 速度，以米/每秒计
                            var accuracy = res.accuracy; // 位置精度
                        },
                        fail:function(res){
                            alert('位置获取失败');
                        }
                    });
                    wx.openLocation({
                        latitude: 40.14, // 纬度，浮点数，范围为90 ~ -90
                        longitude: 116, // 经度，浮点数，范围为180 ~ -180。
                        name: '问建梁家', // 位置名
                        address: '六号楼Q', // 地址详情说明
                        scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
                        infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
                    });



            });




        });
    });

</script>



