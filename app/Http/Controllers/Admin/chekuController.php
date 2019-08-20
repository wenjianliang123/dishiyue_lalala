<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class chekuController extends Controller
{
    public function login()
    {
        return view('admin.cheku.login');
    }
    public function dologin()
    {
        $data=request()->all();
        if(empty($data['user_name'])){
            echo "<script>alert('用户名不能为空');history.back(0)</script>";die;
        }
        if(empty($data['user_pwd'])){
            echo "<script>alert('密码不能为空');history.back(0)</script>";die;
        }
        $login=DB::connection('mysql_shop')->table('qiehuan_user')->where('user_name',$data['user_name'])->first();
        if(empty($login)){
            echo "<script>alert('用户名不存在');history.back(0)</script>";die;
        }
        if($data['user_pwd']!=$login->user_pwd){
            echo "<script>alert('密码错误');history.back(0)</script>";die;
        }
        session(['loginInfo'=>$data]);
        return redirect('admin/cheku/index');
    }
    public function index()
    {
//        $ss='zp,yf,x';
//        dd(base64_encode($ss));die;
        return view('admin.cheku.index');
    }
    public function addcar()
    {
        return view('admin.cheku.addcar');
    }
    public function doaddcar()
    {
        $data=request()->all();
        unset($data['_token']);
        $carInfo=DB::connection('mysql_yuekao')->table('car')->first();
        if(!empty($carInfo)){
            $data['shengyu']= $carInfo->shengyu+$data['sum'];
            $data['sum'] += $carInfo->sum;
            DB::connection('mysql_yuekao')->table('car')->where('id',$carInfo->id)->update($data);
        }else{
            $data['shengyu']=$data['sum'];
            DB::connection('mysql_yuekao')->table('car')->insert($data);
        }
        return redirect('admin/cheku/index');
    }
    public function addmenwei()
    {
        return view('admin.cheku.addmenwei');
    }
    public function doaddmenwei()
    {
        $data=request()->all();
        if(empty($data['user'])){
            echo "<script>alert('门卫名字不能为空');history.back(0)</script>";die;
        }
        unset($data['_token']);
        DB::connection('mysql_yuekao')->table('menwei')->insert($data);
        return redirect('admin/cheku/index');
    }
    public function admin()
    {
        $data=DB::connection('mysql_yuekao')->table('car')->first();
        return view('admin.cheku.admin',['data'=>$data]);
    }
    public function carin()
    {
        return view('admin.cheku.carin');
    }
    public function docarin()
    {
        $data=request()->all();
        if(empty($data['chepaihao'])){
            echo "<script>alert('车牌号不能为空');history.back(0)</script>";die;
        }
        $inoutInfo=DB::connection('mysql_yuekao')->table('inout')->where('chepaihao',$data['chepaihao'])->where('outtime',0)->first();
        if(!empty($inoutInfo)){
            echo "<script>alert('该车牌号未出库');history.back(0)</script>";die;
        }
        unset($data['_token']);
        $data['intime']=time();
        DB::connection('mysql_yuekao')->table('inout')->insert($data);
        $carInfo=DB::connection('mysql_yuekao')->table('car')->first();
        DB::connection('mysql_yuekao')->table('car')->where('id',$carInfo->id)->update([
            'shengyu'=>$carInfo->shengyu-1,
            'num'=>$carInfo->num+1,
        ]);
        return redirect('admin/cheku/admin');
    }
    public function carout()
    {
        return view('admin.cheku.carout');
    }
    public function docarout()
    {
        $data=request()->all();
        if(empty($data['chepaihao'])){
            echo "<script>alert('车牌号不能为空');history.back(0)</script>";die;
        }
        $inoutInfo=DB::connection('mysql_yuekao')->table('inout')->where('chepaihao',$data['chepaihao'])->where('intime','>',0)->where('outtime',0)->first();
        if(empty($inoutInfo)){
            echo "<script>alert('该车牌号未进库');history.back(0)</script>";die;
        }
        unset($data['_token']);
        $data['outtime']=time();
        $chaji=time()-($inoutInfo->intime);
        if($chaji<900){//十五分钟不算钱
            $data['money']=0;
        }else{
            if($chaji>=900&&$chaji<3600*6){//大于十五分钟小于6个小时
                $ban=(int)ceil($chaji/1800);
                $data['money']=$ban*2;//半个小时两块钱
            }else{
                $zheng=(int)ceil($chaji/1800);
                $data['money']=24+($zheng-6);
            }
        }
        DB::connection('mysql_yuekao')->table('inout')->where('id',$inoutInfo->id)->update($data);
        $carInfo=DB::connection('mysql_yuekao')->table('car')->first();
        DB::connection('mysql_yuekao')->table('car')->where('id',$carInfo->id)->update([
            'shengyu'=>$carInfo->shengyu+1,
            'money'=>$carInfo->money+$data['money'],
        ]);
        return redirect()->action('Admin\chekuController@detail',['id'=>$inoutInfo->id]);
    }
    public function detail()
    {
        $id=request()->get('id');
        $data=DB::connection('mysql_yuekao')->table('inout')->where('id',$id)->first();
        $chaji=($data->outtime)-($data->intime);
        if($chaji>3600){
            $hours=(int)floor($chaji/60*60);
            $minute=(int)ceil(($chaji-$hours*3600)/60);
        }else{
            $minute=(int)ceil($chaji/60);
            $hours=0;
        }
        return view('admin.cheku.detail',['data'=>$data,'hours'=>$hours,'minute'=>$minute]);
    }
    public function info()
    {
        $data=DB::connection('mysql_yuekao')->table('car')->first();
        return view('admin.cheku.info',['data'=>$data]);
    }
    public function logout(Request $request)
    {
        $request->session()->forget('loginInfo');
        return redirect('login');
    }
}
