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
        return view("admin/goods/add");
    }
    public function do_add(Request $request)
    {
        $data=$request->except(['_token']);
        $path=$request->file('goods_picture')->store('/goods/goods_picture');
//        dd($path);
        $path_url=asset('storage/'.$path);
        $data['goods_picture']=$path_url;
        $data['add_timr']=time();
//        $res=DB::connection('qiehuan')->table('goods')->insert($data);
//        if ($res)
//        {
//            echo 111;
////            return redirect('admin/goods/index');
//        }else{
//            echo "no_add";
//        }
    }

}
