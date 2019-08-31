<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class StratorController extends Controller
{
    //登陆页面
    public function strator_add()
    {
        return view('admin.strator_add');
    }
    //登完跳转
    public function strator_add_do(Request $request)
    {
         $data=$request->all();
         $info=DB::connection('mysql_shop')->table('login')->insert([
             'name'=>$data['name'],
             'pwd'=>md5($data['pwd']),
         ]);
        if($info){
            return redirect('strator_list');
        }
    } 
    public function strator_list()
    {
        return view('admin.strator_list');
    }

    //单选添加
    public function stratot_list_add(Request $request)
    {
        $data=$request->all();
//        dd($data);
        $info=DB::connection('mysql_shop')->table('danxuan')->insert([
             'danxuan_wenti'=>$data['danxuan_wenti'],
             'danxuan_xuanxiang'=>$data['danxuan_xuanxiang'],
             'danxuan_daan_A'=>$data['danxuan_daan_A'],
             'danxuan_daan_B'=>$data['danxuan_daan_B'],
             'danxuan_daan_C'=>$data['danxuan_daan_C'],
             'danxuan_daan_D'=>$data['danxuan_daan_D'],
        ]);
        dd($info);
    }
    //多选添加

    /**
     * @param Request $request
     */
    public function strator_list_acc(Request $request)
    {
        $data=$request->all();
//        dd($data);


//        if(empty($data['duoxuan_xuanxiang_A']) || empty($data['duoxuan_xuanxiang_B'])||empty($data['duoxuan_xuanxiang_C']) || empty($data['duoxuan_xuanxiang_D'])){
//            @$duoxuan_xuanxiang=$data['duoxuan_xuanxiang_A'].$data['duoxuan_xuanxiang_B'].$data['duoxuan_xuanxiang_C'].$data['duoxuan_xuanxiang_D'];
//        }
//        dd($duoxuan_xuanxiang);
        $duoxuan_xuanxiang=$data['duoxuan_xuanxiang'];
        $duoxuan_xuanxiang=implode($duoxuan_xuanxiang);
        dd($duoxuan_xuanxiang);


        $info=DB::connection('mysql_shop')->table('duoxuan')->insert([
            'duoxuan_wenti'=>$data['duoxuan_wenti'],
            'duoxuan_xuanxiang'=>$duoxuan_xuanxiang,
            'duoxuan_daan_A'=>$data['duoxuan_daan_A'],
            'duoxuan_daan_B'=>$data['duoxuan_daan_B'],
            'duoxuan_daan_C'=>$data['duoxuan_daan_C'],
            'duoxuan_daan_D'=>$data['duoxuan_daan_D'],
        ]);
        dd($info);
    }

    public function strator_list_abb(Request $request)
    {
        $data=$request->all();
        //dd($data);
        $info=DB::connection('mysql_shop')->table('panduan')->insert([
             'aaaa'=>$data['aaaa'],
             'bbbb'=>$data['bbbb'],
        ]);
        dd($info);
    }
    public function strator_list_fff()
    {
        return view('admin.strator_list_fff');
    }
    public function strator_list_do(Request $request)
    {
        $data=$request->all();
        //dd($data);
        $info=DB::connection('mysql_shop')->table('shi')->insert([
             'title'=>$data['title'],
        ]);
        if($info){
            return redirect('strator_list_list');
        }
    }
    public function strator_list_list(Request $request)
    {  
       $data=$request->all();
       $info=DB::connection('mysql_shop')->table('shi')->get();
       return view('admin.strator_list_list',['data'=>$info]);
    }

   
    
}
