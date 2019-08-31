<?php

namespace App\Http\Controllers\Index;

use App\Http\Model\goods_model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class indexController extends Controller
{
    //商品列表页面
    public function index()
    {
        $new_goods=DB::connection('mysql_index')->table("goods")->where('is_new','=',1)->get();
        $top_goods=DB::connection('mysql_index')->table("goods")->where('is_new','=',2)->get();
//        dd($new_goods);
//        dd($top_goods);
        return view("index/index",['data'=>$new_goods,'data1'=>$top_goods]);
    }
    //商品详情页面
    public function goods_detail($goods_id)
    {

//      dd($goods_id);
//        dump($request->goods_id);die;
//
        $data=DB::connection('mysql_index')
            ->table('goods')
            ->where('goods_id',$goods_id)
            ->first();
//        dd($data);
        return view("index/goods/goods_detail",['goods_detail'=>$data]);
    }
    //在详情页面点击加入购物车
    public function buy_cart(Request $request){
        //接受登陆后的详情页面传过来的是哪个商品id
        $goods_id=$request->goods_id;
        //接受登陆后的用户名
        $shuliang=$request->shuliang;

        //取整形
        $shuliang=intval($shuliang);

        //接受登陆后的用户名
        $user_name=session('user_name');

        //查询购物车里是否有该商品
        $cart_info=DB::connection('mysql_index')
            ->table('cart')
            ->where('user_name',$user_name)
            ->where('goods_id',$goods_id)
            ->first();
//         dd($cart_info);

        //查询购物车表里面的购买数量
        $buy_number=DB::connection('mysql_index')
            ->table('cart')
            ->where('goods_id',$goods_id)
            ->where('user_name',$user_name)
            ->value('buy_number');

//        dd($buy_number);
//        dd($buy_number);

//        //重复加入购物车时要修改的购买数量
        $total_number=$shuliang+$buy_number;
//        dd($total_number);
//
//        //获取单价
        $goods_price=goods_model::where('goods_id',$goods_id)->value('goods_price');
//        dd($goods_price);



//        //获取库存
        $goods_number=goods_model::where('goods_id',$goods_id)->value('goods_number');
//        dd($goods_number);
//
//        //修改购买数量时的总价
        $toto_price=$total_number*$goods_price;
//         dump($toto_price);die;
//
//        //定义总价 第一次加入购物车
        $total_price=$shuliang*$goods_price;
//         dump($total_price);die;
//

//       // 判断是否登录
            //判断session中是否存在 是的话 返回true 不是false
        if ($request->session()->has('user_name')) {
//
            $goods_id=intval($goods_id);
//             dump($goods_id);die();
//             dump($shuliang);die;
            //判断是整形或者字符串的数字
            if(is_numeric($goods_id)&&is_numeric($shuliang)){
                if ($cart_info) {
                    //已加入购物车 修改购物数量
                    // echo 1;
                    if ($total_number>=$goods_number) {
                            echo json_encode(['font'=>'超出库存','code'=>2,'icon'=>2]);
                    }else{
//                        $sss=['buy_number'=>$total_number];
                        $status=DB::connection('mysql_index')->table('cart')->where('goods_id',$goods_id)->where('user_name',$user_name)->update([
                            'buy_number'=>$total_number,
                            'add_time'=>time(),
                        ]);
                        if ($status) {
                            echo json_encode(['font'=>'重复加入购物车成功','icon'=>1,'code'=>1]);die;
                        }else{
                            echo json_encode(['font'=>'加入购物车失败','icon'=>2,'code'=>2]);die;
                        }
                    }
                }else{
                    //第一次加入购物车
                    if ($shuliang>$goods_number) {
                        echo json_encode(['font'=>'超出库存','code'=>2,'icon'=>2]);
                    }else{
                        $data=['goods_id'=>$goods_id,'buy_number'=>$shuliang,'user_name'=>$user_name];
                        // dump($data);die;
                        $status=DB::connection('mysql_index')->table('cart')->insert([
                            'goods_id'=>$goods_id,
                            'buy_number'=>$shuliang,
                            'user_name'=>$user_name,
                            'add_time'=>time(),
                        ]);
                        if ($status) {
                            echo json_encode(['font'=>'第一次加入购物车成功','icon'=>1,'code'=>1]);die;
                        }else{
                            echo json_encode(['font'=>'第一次加入购物车失败','icon'=>2,'code'=>2]);die;
                        }
                    }
                }
            }else{
                echo json_encode(['font'=>'参数数字格式有误','code'=>2,'icon'=>2]);exit;
            }
        }else{
            //未登录
            /* dump(cookie('cart_cookie'));*/
            /**
             * 用PHP的json_encode来处理中文的时候,
             * 中文都会被编码, 变成不可读的, 类似”\u***”的格式,
             * 还会在一定程度上增加传输的数据量.
             * JSON_UNESCAPED_UNICODE, 故名思议, 就是说, Json不要编码Unicode
             */
            echo json_encode(['font'=>'未登录','code'=>2,'url'=>'index/login/login'],JSON_UNESCAPED_UNICODE);exit;

        }

//
    }
    //显示购物车里列表 去订单页面
    public function buy_cart_1()
    {
        $user_name=session('user_name');

        $data1 = DB::connection('mysql_index')->table('goods')
            ->where('user_name',$user_name)
            ->join('cart', 'goods.goods_id', '=', 'cart.goods_id')
            ->select('goods.*', 'cart.*')
            ->get()
            ->toArray();
        /**
         * 为了得到集合中的东西 将其变为数组
         * array_map() 函数将用户自定义函数作用到数组中的每个值上，并返回用户自定义函数作用后的带有新值的数组。
         * 回调函数接受的参数数目应该和传递给 array_map() 函数的数组数目一致。
         */
        $data1=array_map('get_object_vars', $data1);

//        dd($data1);

//        $goods_price=goods_model::where('goods_id',$data1['goods_id'])->get()->value('goods_price');
//        dd($goods_price);
        $total=0;
        foreach ($data1 as $k => $v) {
            $sub_total=floor($v['goods_price'])*floor($v['buy_number']);
            $data1[$k]['sub_total']=$sub_total;
            $total+=$sub_total;
        }
//        dd($total);
        return view('/index/goods/buy_cart_1',['data1'=>$data1,'total'=>$total]);
    }
    //去订单页面
    public  function order(Request $request)
    {
        /**********************************订单表操作**********************************/
        //订单表入库
        $user_name=session('user_name');
//        dd($user_name);
        $goods_id=$request->goods_id;
//        dd($goods_id);

        $shuliang=$request->shuliang;
        $shuliang=array($shuliang);

        //查看订单表里面有无数据
        $order_info=DB::connection('mysql_index')
            ->table('order')
            ->where('user_name',$user_name)
            ->first();
//        dd($order_info);

        // 订单表有则修改、无则添加
        if($order_info==null){
            //添加
            $goods_id=explode(",", $goods_id);
            //dd($goods_id);
            $data1 = DB::connection('mysql_index')->table('cart')
                ->where('user_name',$user_name)
                ->whereIn('goods.goods_id',$goods_id)
                ->join('goods', 'cart.goods_id', '=', 'goods.goods_id')
                ->select('goods.*', 'cart.*')
                ->get()
                ->toArray();
            //dd($data1);
            //求总价
            $data1=array_map('get_object_vars', $data1);

            $total=0;
            foreach ($data1 as $k => $v) {
                $sub_total=floor($v['goods_price'])*floor($v['buy_number']);
                $data1[$k]['sub_total']=$sub_total;
                $total+=$sub_total;
            }
            //dd($total);

            $goods_id=implode(",", $goods_id);
            $order_info=DB::connection('mysql_index')
                ->table('order')
                ->insert([
                    'goods_id'=>$goods_id,
                    'user_name'=>$user_name,
                    'add_time'=>time(),
                    'status'=>1,
                    'total_price'=>$total,
                ]);

            //dd($data1);


            if($order_info){
                echo json_encode(['font'=>'订单第一次添加成功','code'=>1],JSON_UNESCAPED_UNICODE);exit;
            }else{
                echo json_encode(['font'=>'订单第一次添加失败','code'=>2],JSON_UNESCAPED_UNICODE);exit;
            }

        }else{
            //修改
            $goods_id=explode(",", $goods_id);
            //dd($goods_id);
            $data1=DB::connection('mysql_index')->table('cart')
                ->where('user_name',$user_name)
                ->whereIn('goods.goods_id',$goods_id)
                ->join('goods','cart.goods_id','=','goods.goods_id')
                ->select('goods.*','cart.*')
                ->get()
                ->toArray();
            //dd($data1);
            //求总价
            $data1=array_map('get_object_vars', $data1);

            $total=0;
            foreach ($data1 as $k => $v) {
                $sub_total=floor($v['goods_price'])*floor($v['buy_number']);
                $data1[$k]['sub_total']=$sub_total;
                $total+=$sub_total;
            }
            //dd($total);

            $goods_id=implode(",",$goods_id);
            //dd($goods_id);
            $order_info=DB::connection('mysql_index')
                ->table('order')
                ->where('user_name',$user_name)
                ->update([
                    'goods_id'=>$goods_id,
                    'user_name'=>$user_name,
                    'add_time'=>time(),
                    'status'=>1,
                    'total_price'=>$total,
                ]);

            if($order_info){
                echo json_encode(['font'=>'订单重复添加（修改）成功','code'=>1,'icon'=>1],JSON_UNESCAPED_UNICODE);
            }else{
                echo json_encode(['font'=>'订单重复添加（修改）失败','code'=>2,'icon'=>2],JSON_UNESCAPED_UNICODE);exit;
            }


        }
        /**********************************订单表操作**********************************/



        /**********************************订单详情表操作**********************************/


        /**
         ****1*******1*****1********添加或修改到订单详情表（可使用）******1*****1******1***
         */

//            dd($user_name);
        //上面die掉了

        $order_detail_info=DB::connection('mysql_index')->table('order_detail')
            ->where('user_name',$user_name)
            ->value('user_name');

//            dd($order_detail_info);

        if($order_detail_info==null){
            //添加订单详情表
            $goods_picture='';
            $goods_name='';
            $buy_number='';
            foreach ($data1 as $v)
            {
                $goods_picture.='|'.$v['goods_picture'];
                $goods_name.=','.$v['goods_name'];
                $buy_number.=','.$v['buy_number'];
            }

            $goods_picture=trim($goods_picture,'|');
            $goods_name=trim($goods_name,',');
            $buy_number=trim($buy_number,',');
            //            $goods_picture = explode('|',$goods_picture);
            //            dd($goods_picture,$goods_name,$buy_number);

            $order_detail_add=DB::connection('mysql_index')
                ->table('order_detail')
                ->insert([
                    'goods_id'=>$goods_id,
                    'user_name'=>$user_name,
                    'goods_name'=>$goods_name,
                    'goods_picture'=>$goods_picture,
                    'buy_number'=>$buy_number,
                    'add_time'=>time(),
                ]);

//            if($order_detail_add){
//                dump('订单详情表第一次添加成功');
//            }else{
//                dumo('订单详情表第一次添加失败');die();
//            }

        }else{
            //修改订单详情表
            $goods_picture='';
            $goods_name='';
            $buy_number='';
            foreach ($data1 as $v)
            {
                $goods_picture.='|'.$v['goods_picture'];
                $goods_name.=','.$v['goods_name'];
                $buy_number.=','.$v['buy_number'];
            }

            $goods_picture=trim($goods_picture,'|');
            $goods_name=trim($goods_name,',');
            $buy_number=trim($buy_number,',');
            //            $goods_picture = explode('|',$goods_picture);
            //            dd($goods_picture,$goods_name,$buy_number);

            $order_detail_add=DB::connection('mysql_index')
                ->table('order_detail')
                ->where('user_name',$user_name)
                ->update([
                    'goods_id'=>$goods_id,
                    'user_name'=>$user_name,
                    'goods_name'=>$goods_name,
                    'goods_picture'=>$goods_picture,
                    'buy_number'=>$buy_number,
                    'add_time'=>time(),
                ]);

//            if($order_detail_add){
//                 dump('订单详情表第一次修改成功');
//            }else{
//                dump('订单详情表第一次修改失败');die();
//            }
        }






        /**********************************订单详情表操作**********************************/

    }
    //订单显示页面  ！！！！！！！！！！！！！！！！不能用！！！！！！！！！！！！！！
    public function do_order()
    {

        $user_name=session('user_name');
     /*
        $goods_id=DB::connection('mysql_index')->table('order')
            ->where('user_name',$user_name)
            ->select('goods_id')
            ->get()
            ->toArray();


        $goods_id=implode($goods_id);
        $goods_id=explode(',',$goods_id);
        $goods_id=array_map('get_object_vars',$goods_id);
//        $goods_id=explode(',',$goods_id);
        dd($goods_id);

//        dd($goods_id);
//        $goods_id=array_map('get_object_vars',$goods_id);
        dd($goods_id);*/



        $data=DB::connection('mysql_index')->table('order')
            ->where('user_name',$user_name)
            ->get()->toarray();
        // dd($data);
        $goods_id=array_column($data,'goods_id');
        // dd($goods_id);
        $goods_id=implode(',',$goods_id);
//        dd($goods_id);


        $goods_id=explode(',',$goods_id);

//            dd($goods_id);
        // 开启 log
        DB::connection('mysql_index')->enableQueryLog();
        //查出order表信息
        //根据order的goods_id拆成[18,19] whereIn

        $data1 = DB::connection('mysql_index')->table('cart')
            ->whereIn('goods.goods_id',$goods_id)
            ->rightJoin('goods', 'cart.goods_id', '=', 'goods.goods_id')
            ->select('goods.*','cart.*')
            ->get()
            ->toArray();
//        dd($data1);
        // 获取已执行的查询数组
//                dd(DB::connection('mysql_index')->getQueryLog());


//        dd($data1);
        /**
         * 为了得到集合中的东西 将其变为数组
         * array_map() 函数将用户自定义函数作用到数组中的每个值上，并返回用户自定义函数作用后的带有新值的数组。
         * 回调函数接受的参数数目应该和传递给 array_map() 函数的数组数目一致。
         */
        $data1=array_map('get_object_vars', $data1);

//        dd($data1);

//        $goods_price=goods_model::where('goods_id',$data1['goods_id'])->get()->value('goods_price');
//        dd($goods_price);
        $total=0;
        foreach ($data1 as $k => $v) {
            $sub_total=floor($v['goods_price'])*floor($v['buy_number']);
            $data1[$k]['sub_total']=$sub_total;
            $total+=$sub_total;
        }
//        dd($total);
        return view('/index/goods/order_index',['data1'=>$data1,'total'=>$total]);
    }





}



