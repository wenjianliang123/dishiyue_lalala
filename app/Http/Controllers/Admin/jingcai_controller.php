<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class jingcai_controller extends Controller
{
    //
    public function add()
    {
//        echo 1;die;
        return  view('admin/jingcai_1/add');
    }
    public function do_add()
    {
        $data=$_POST;
//        dd($data);
        $time=$data['jieshu_time'];
        $jieshu_time=strtotime($time);
//        dd($add_time);
        $result=DB::table('jingcai_houtai')->insert([
           'qiudui_1'=>$data['qiudui_1'],
           'qiudui_2'=>$data['qiudui_2'],
           'jieshu_time'=>$jieshu_time,
        ]);
        if($result){
            return redirect('admin/jingcai/index');
        }else{
            echo "<script>history.back()</script>";
        }
    }

    public function index(){
        $data=DB::table('jingcai_houtai')->get()->toarray();


        return view('admin/jingcai_1/index',['data'=>$data,]);

    }

    public function yonghu_guess(Request $request){
        $jingcai_id=$request->jingcai_id;
//        dd($jingcai_id);
        $data=DB::table('jingcai_houtai')->where('jingcai_id',$jingcai_id)->first();
        return view('admin/jingcai_1/yonghu_guess',['data'=>$data]);
    }
    public function do_guess(Request $request){
        $data=$request->all();
//        dd($data);
        $result=DB::table('jingcai_yonghu')->insert([
            'jingcai_id'=>$data['jingcai_id'],
            'xuanze'=>$data['xuanze'],
            'add_time'=>time(),
        ]);
        if($result){
            return redirect('admin/jingcai/index');
        }else{
            echo "<script>history.back()</script>";
        }
    }

    public function chakan_bisai_jieguo(Request $request)
    {
        $jingcai_id=$request->jingcai_id;
        $data=DB::table('jingcai_houtai')->where('jingcai_id',$jingcai_id)->first();
        $is_guess=DB::table('jingcai_yonghu')->where('jingcai_id',$jingcai_id)->first();
//        dd($is_guess);
        //能查到可以看精彩结果 否侧时间到了出查看结果显示你没有进行竞猜
        if(!empty($is_guess)){
            return view('admin/jingcai_1/chakan_jingcai_jieguo',['data'=>$data,'yonghu_guess'=>$is_guess]);
        }else{
            //没有竞猜
            return view('admin/jingcai_1/chakan_bisai_jieguo',['data'=>$data]);
        }


    }
}
