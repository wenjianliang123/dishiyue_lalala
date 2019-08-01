<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class diyicizhoukao0715 extends Controller
{
    public function add()
    {
        return view("admin/zhoukao/add");
    }

    public function do_add(Request $request)
    {
        $data=$request->except(['_token']);
//        dd($data);
        $data['add_time']=time();
        if($request->file('goods_picture')=='')
        {
            $res=DB::table('goods_yizhoukao')->insert([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'add_time'=>$data['add_time'],
            ]);
            if ($res){
                return redirect('/admin/zhoukao/index');
            }
        }else{
            $path=$request->file('goods_picture')->store('/goods_picture');
            $path_url='storage/'.$path;
            $data['goods_picture']=$path_url;

//        dd($data);
            $res=DB::table('goods_yizhoukao')->insert([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'goods_picture'=>$data['goods_picture'],
                'add_time'=>$data['add_time'],
            ]);
            if ($res){
                return redirect('admin/zhoukao/index');
            }
        }
    }

    public function index(Request $request)

    {
        $redis= new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num1');
//        $data=DB::table('goods')->get();
        //dd($data);
        $req =$request->all();
//        dd($req);
        if (isset($req['goods_name'])){
            $goods_info=DB::table('goods_yizhoukao')->where('goods_name','like','%'.$req['goods_name'].'%')->orderBy("goods_id","desc")->paginate(1);
        }else{
            $req['goods_name']='';
            $goods_info=DB::table('goods_yizhoukao')->orderBy("goods_id","desc")->paginate(2);
        }

        $goods_infomation=$goods_info->toArray();
        $goods_infomation=json_encode($goods_infomation);
        $redis->set('goods_infomation',$goods_infomation,60);
        $num=$redis->get('num1');
        echo "访问次数为".$num."次"."<br/>";

//        $aa=strtotime('9:00:00');
//        $bb=strtotime('17:00:00');
//        dd($bb);

        return view("/admin/zhoukao/index",['data'=>$goods_info,'goods_name'=>$req['goods_name']]);


    }

    public function edit($id)
    {
        $data=DB::table('goods_yizhoukao')->where('goods_id','=',$id)->first();
//        dd($data);
        return view('/admin/zhoukao/edit',['data'=>$data]);
    }

    public function update(Request $request )
    {
        $goods_id=$request->goods_id;
        $data=$request->except(['_token','goods_id']);
        $data['add_time']=time();
//
        if($request->file('goods_picture')=='')
        {
            $res=DB::table('goods_yizhoukao')->where(['goods_id'=>$goods_id])->update([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'add_time'=>$data['add_time'],
            ]);
            if ($res){
                return redirect('/admin/zhoukao/index');
            }
        }else{
            $path=$request->file('goods_picture')->store('/goods_picture');
            $path_url='storage/'.$path;
            $data['goods_picture']=$path_url;

//        dd($data);
            $res=DB::table('goods_yizhoukao')->where(['goods_id'=>$goods_id])->update([
                'goods_name'=>$data['goods_name'],
                'goods_number'=>$data['goods_number'],
                'goods_picture'=>$data['goods_picture'],
                'add_time'=>$data['add_time'],
            ]);
            if ($res){
                return redirect('admin/zhoukao/index');
            }
        }

    }

    public function del($id)
    {
        $res=DB::table('goods_yizhoukao')->where('goods_id','=',$id)->delete();
//        dd($res);
        if ($res){
            return redirect('/admin/zhoukao/index');
        }
    }
}
