<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class loginController extends Controller
{
    public function login()
    {
        return view("index/login/login");
    }

    public function do_login(Request $request)
    {
        $data=$request->except(['_token']);
//        dd($data);
        //laravel查询构造器切换数据库并查询
        $user_info = DB::connection('mysql_shop')->table('qiehuan_user')->where(['user_name'=>$data['user_name'],'user_pwd'=>$data['user_pwd']])->first();
//        dd($user_info);
        if(isset($user_info))
        {
            $request->session()->put('user_name', $data['user_name']);
            return view('admin/success')->with([
                //跳转信息
                'message'=>'你已经提交信息，请您耐心等待！',
                //自己的跳转路径
                'url' =>asset('/'),
                //跳转路径名称
                'urlname' =>'商品列表',
                //跳转等待时间（s）
                'jumpTime'=>2,
            ]);
        }else{
            echo '账号或密码有误';
        }
    }

    public function register(){
        return view('index/login/register');
    }

    public function do_register(Request $request)
    {
        $data=$request->except(['_token','user_repwd']);
//        dd($data);
        $result=DB::connection('mysql_shop')->table('qiehuan_user')->insert($data);

        if($result)
        {
//            echo '<script>alert("注册成功");window.location.href="admin/student/index";</script>';

//            return redirect("admin/student/index");
            return view('admin/success')->with([
                //跳转信息
                'message'=>'你已经提交信息，请您耐心等待！',
                //自己的跳转路径
                'url' =>asset('index/login/login'),
                //跳转路径名称
                'urlname' =>'登陆页面',
                //跳转等待时间（s）
                'jumpTime'=>2,
            ]);

        }else{
            echo "<script>alert('添加失败!');history.back();</script>";
//            return redirect("admin/login/register");
        }


    }




}
