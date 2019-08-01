<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class goodsController extends Controller
{
    /**
     * 本篇代码 为 老师那种一个字段一个字段加入的方法进行尝试
     *
     * 而且会采用ajax进行curd操作
     */

    public  function add()
    {
//        setcookie('user','zhangsan',300);
//        var_dump($_COOKIE['user']);die();
        return view("admin/goods/add");
    }
    public function do_add(Request $request)
    {
        $data=$request->except(['_token']);
//        dd($data);
        $data['add_time']=time();
        if($request->file('goods_picture')=='')
        {
            $res=DB::connection('mysql_index')->table('goods')->insert([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'goods_price'=>$data['goods_price'],
                'add_time'=>$data['add_time'],
                'is_new'=>$data['is_new'],
            ]);
            if ($res){
                return redirect('/admin/goods/index');
            }
        }else{
            $path=$request->file('goods_picture')->store('/goods_picture');
            $path_url='storage/'.$path;
            $data['goods_picture']=$path_url;

//        dd($data);
            $res=DB::connection('mysql_index')->table('goods')->insert([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'goods_price'=>$data['goods_price'],
                'goods_picture'=>$data['goods_picture'],
                'add_time'=>$data['add_time'],
                'is_new'=>$data['is_new'],
            ]);
            if ($res){
                return redirect('admin/goods/index');
            }
        }

    }

    public function index(Request $request)
    {
        $redis= new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num');
//        $data=DB::table('goods')->get();
        //dd($data);
        $req =$request->all();
//        dd($req);
        if (isset($req['goods_name'])){
            $goods_info=DB::connection('mysql_index')->table('goods')->where('goods_name','like','%'.$req['goods_name'].'%')->paginate(1);
        }else{
            $req['goods_name']="";
            $goods_info=DB::connection('mysql_index')->table('goods')->paginate(2);
        }

        $goods_infomation=$goods_info->toArray();
        $goods_infomation=json_encode($goods_infomation);
        $redis->set('goods_infomation',$goods_infomation,60);
        $num=$redis->get('num');
        echo "访问次数为".$num."次"."<br/>";

//        $aa=strtotime('9:00:00');
//        $bb=strtotime('17:00:00');
//        dd($bb);

        return view("/admin/goods/index",['data'=>$goods_info,'goods_name'=>$req['goods_name']]);


    }
    public function del($id)
    {
        $res=DB::connection('mysql_index')->table('goods')->where('goods_id','=',$id)->delete();
//        dd($res);
        if ($res){
            return redirect('/admin/goods/index');
        }
    }
//
    public function edit($id)
    {
        $data=DB::connection('mysql_index')->table('goods')->where('goods_id','=',$id)->first();
//        dd($data);
        return view('/admin/goods/edit',['data'=>$data]);
    }

    public function update(Request $request )
    {
        /*dd($data);*/
//        dd($data);
        $goods_id=$request->goods_id;
        $data=$request->except(['_token','goods_id']);
        $data['add_time']=time();
//        dd($data);
//        dd($data['goods_img']);
//        dd($request->file('goods_img'));
        if($request->file('goods_picture')=='')
        {
            //$path="D:/"."wnmp/"."www/"."myShop/"."storage/"."app/"."public/".$request->file('goods_img')->store('/goods_img');
//        //这里注意应该全都改为反斜杠
//        $path=str_replace('/','\\',$path);
//        dd($path);
//            $path=$request->file('goods_img')->store('/goods_img');
//            $path_url=asset('storage/'.$path);
//            $data['goods_img']=$path_url;

//        dd($data);

            $res=DB::connection('mysql_index')->table('goods')->where(['goods_id'=>$goods_id])->update([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'goods_price'=>$data['goods_price'],
                'add_time'=>$data['add_time'],
                'is_new'=>$data['is_new'],
            ]);
            if ($res){
                return redirect('/admin/goods/index');
            }
        }else{
            $path=$request->file('goods_picture')->store('/goods_picture');
            $path_url='storage/'.$path;
            $data['goods_picture']=$path_url;

//        dd($data);
            $res=DB::connection('mysql_index')->table('goods')->where(['goods_id'=>$goods_id])->update([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'goods_price'=>$data['goods_price'],
                'goods_picture'=>$data['goods_picture'],
                'add_time'=>$data['add_time'],
                'is_new'=>$data['is_new'],
            ]);
            if ($res){
                return redirect('admin/goods/index');
            }
        }

    }
}
