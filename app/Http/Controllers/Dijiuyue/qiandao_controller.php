<?php

namespace App\Http\Controllers\Dijiuyue;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class qiandao_controller extends Controller
{

    public function index()
    {
        $isContinue=false;//是否连续签到
        //签到功能
        $data = DB::connection('mysql_shop')->table('qian_dao')->where('user_id',1)->orderBy('sign_id','desc')->first();//取当前用户最后一次签到的数据
        if($data){
            $time =$data->sign_time;
            //获取当前凌晨的时间
            $time0 = date("Y-m-d");//当前凌晨格式化时间
            $time0=strtotime($time0);
            if ($time>$time0) {
                echo "<script>alert('亲，您已签到过了');</script>";die;
            }
            //判断时间是否是连续签到
            if ($time<$time0 && $time>$time0-86400) {
                $isContinue=true;
            }
        }
        DB::connection('mysql_shop')->table('qian_dao')->insert([
            'sign_time'=>time(),
            'user_id'=>1
        ]);
        //送分 修改用户积分表
        $userDdata = DB::connection('mysql_shop')->table('qian_dao_user')->where("user_id",1)->first();
//        dd($userDdata);
        $day = $userDdata->sign_day;
        $fen = $userDdata->integral;
        if ($isContinue && $day<5) {
            $day = $day+1;
            $fen = $fen+$day*5;
        }else{
            $day = 1;
            $fen =$fen+5;
        }
        $res=DB::connection('mysql_shop')->table('qian_dao_user')->where('user_id',1)->update([
            'integral'=>$fen,'sign_day'=>$day
        ]);
        echo "<script>alert('签到成功');</script>";die;
    }

}
