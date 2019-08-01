<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class diaoyanController extends Controller
{
//    调研项目的添加页面
    public function diaoyan_xiangmu_add()
    {
        return view('admin.diaoyan.add');
    }

    //调研项目的执行添加
    public function diaoyan_xiangmu_do_add(Request $request)
    {
        $data=$request->all();
//        dd($data);
        $info=DB::connection('mysql_shop')->table('diaoyan_xiangmu')->insertGetId([
           'diaoyan_name'=>$data['diaoyan_name']
        ]);
//        dd($info);
        if($info){
            echo "<script>alert('ok');</script>";
            return view('admin.diaoyan.wenti1_add',['info'=>$info]);
        }
    }

    public function diaoyan_wenti1_do_add(Request $request)
    {
        $data=$request->all();
//        dd($data);
        if(empty($data)){
            return false;
        }

        $info=DB::connection('mysql_shop')->table('diaoyan_xuanxiang')->insertGetId([
            'diaoyan_wenti'=>$data['diaoyan_wenti'],
            'diaoyan_xuanxiang'=>$data['diaoyan_xuanxiang'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
        ]);
//        dd($info);
        if($info){
            if($data['diaoyan_xuanxiang']==1){
                //单选
//                echo "单选1";die;
                    return view('admin.diaoyan.danxuan_wenti',['info'=>$info,'diaoyan_wenti'=>$data['diaoyan_wenti'],'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id']]);
            }else{
                //多选
//                echo "多选2";die;
                return view('admin.diaoyan.fuxuan_wenti',['info'=>$info,'diaoyan_wenti'=>$data['diaoyan_wenti'],'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id']]);
            }
        }else{
            echo "<script>alert('no');</script>";
        }
    }
    //处理单选添加
    public function danxuan_answer_do_add(Request $request){
        $data=$request->all();
//        dd($data);
        DB::connection('mysql_shop')->beginTransaction();
        $info=true;
        $info1=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_a'],
            'is_answer'=>($data['answer_xuanxiang_danxuan']==1)?1:0,
        ]);
        $info2=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_b'],
            'is_answer'=>($data['answer_xuanxiang_danxuan']==2)?1:0,
        ]);
        $info3=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_c'],
            'is_answer'=>($data['answer_xuanxiang_danxuan']==3)?1:0,
        ]);
        $info4=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_d'],
            'is_answer'=>($data['answer_xuanxiang_danxuan']==4)?1:0,
        ]);
        $info=$info1&&$info2&&$info3&&$info4;

        if($info){
            DB::connection('mysql_shop')->commit();
            //添加项目和问题
        }else{
            DB::rollBack();
        }
        dd($info);
    }

    //处理复选添加
    public function fuxuan_answer_do_add(Request $request)
    {
        $data = $request->all();
//        $answer_xuanxiang_fuxuan_a=$data['answer_xuanxiang_fuxuan_a'];
//        dd($data);
        DB::connection("mysql_shop")->beginTransaction();
        $info=true;
        $info1=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_a'],
            'is_answer'=>in_array(1,$data['answer_xuanxiang_fuxuan'])?1:0,
        ]);
        $info2=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_b'],
            'is_answer'=>in_array(2,$data['answer_xuanxiang_fuxuan'])?1:0,
        ]);
        $info3=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_c'],
            'is_answer'=>in_array(3,$data['answer_xuanxiang_fuxuan'])?1:0,
        ]);
        $info4=DB::connection('mysql_shop')->table('diaoyan_answer')->insertGetId([
            'diaoyan_wenti_id'=>$data['diaoyan_wenti_id'],
            'diaoyan_xiangmu_id'=>$data['diaoyan_xiangmu_id'],
            'desc'=>$data['answer_d'],
            'is_answer'=>in_array(4,$data['answer_xuanxiang_fuxuan'])?1:0,
        ]);
        $info=$info1&&$info2&&$info3&&$info4;
        if($info){
            DB::connection('mysql_shop')->commit();
            //添加项目和问题
//            echo "成功！！！";
            return redirect('/admin/diaoyan/diaoyan_list_do_add');
        }else{
            DB::connection('mysql_shop')->rollBack();
//            echo "失败！！！";
        }
        dd($info);
    }

    public function diaoyan_list_do_add()
    {
        echo 111;
    }
}
