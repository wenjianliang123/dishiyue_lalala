<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class loginController extends Controller
{
    public function login()
    {
        return view("admin/login/login");
    }

    public function do_login(Request $request)
    {
        $data=$request->except(['_token']);
        //这个用不了
//        $data1=array_map('get_object_vars', $data);
//        dd($data1);
//        laravel查询构造器切换数据库并查询
//        dd($request->status);
//        if($data['status']==2){
//            $user_info = DB::connection('mysql_shop')
//                ->table('user')
//                ->where(['user_name'=>$data['user_name'],'user_pwd'=>$data['user_pwd'],'status'=>2])->first();
//        }else if($data['status']==4){
//            $user_info = DB::connection('mysql_shop')
//                ->table('user')
//                ->where(['user_name'=>$data['user_name'],'user_pwd'=>$data['user_pwd'],'status'=>4])->first();
//        }else if($data['status']==1){
//            $user_info = DB::connection('mysql_shop')
//                ->table('user')
//                ->where(['user_name'=>$data['user_name'],'user_pwd'=>$data['user_pwd'],'status'=>1])->first();
//        }else{
//            echo "权限不够或已经被禁用";
//        }


        //想做一个普通用户可以访问学生管理上商品管理 管理员可以访问用户列表进行用户权限的修改


        $user_info = DB::connection('mysql_shop')
            ->table('qiehuan_user')
            ->where(['user_name'=>$data['user_name'],'user_pwd'=>$data['user_pwd']])->first();
//        dd($user_info);

        if ($user_info){
            echo "ok判断status";
            $status=DB::connection('mysql_shop')
                ->table('qiehuan_user')
                ->where(['user_name'=>$data['user_name'],'user_pwd'=>$data['user_pwd']])
                ->value('status');
//            dd($status);
            if($status==2){
                $request->session()->put('user_name', $data['user_name']);
                return view('admin/success')->with([
                    //跳转信息
                    'message'=>'尊敬的管理员，您以登陆成功请稍后！',
                    //自己的跳转路径
                    'url' =>asset('admin/user/index'),
                    //跳转路径名称
                    'urlname' =>'管理员界面',
                    //跳转等待时间（s）
                    'jumpTime'=>3,
                ]);
            }else if($status==1){
                $request->session()->put('user_name', $data['user_name']);
                return view('admin/success')->with([
                    //跳转信息
                    'message'=>'尊敬的用户，您以登陆成功请稍后！',
                    //自己的跳转路径
                    'url' =>asset('admin/student/index'),
                    //跳转路径名称
                    'urlname' =>'学生列表',
                    //跳转等待时间（s）
                    'jumpTime'=>3,
                ]);
            }else if($status==3){
                echo "该用户已被禁用";
            }else{
                echo "用户参数错误请重试";
            }
        }else{
            echo '账号或密码有误或权限不够';
        }


        //这个可以用
//        if(isset($user_info))
//        {
//            $request->session()->put('user_name', $data['user_name']);
//            return view('admin/success')->with([
//                //跳转信息
//                'message'=>'你已经提交信息，请您耐心等待！',
//                //自己的跳转路径
//                'url' =>asset('admin/student/index'),
//                //跳转路径名称
//                'urlname' =>'学生列表',
//                //跳转等待时间（s）
//                'jumpTime'=>2,
//            ]);
//        }else{
//            echo '账号或密码有误或权限不够';
//        }
    }

    public function register(){
        return view('admin/login/register');
    }

    public function do_register(Request $request)
    {
        $data=$request->except(['_token','user_repwd']);
//        dd($data);
        $result=DB::connection('mysql_shop')->table('qiehuan_user')->insert([
            'user_name'=>$data['user_name'],
            'user_pwd'=>$data['user_pwd'],
            'status'=>$data['status'],
        ]);

        if($result)
        {
//            echo '<script>alert("注册成功");window.location.href="admin/student/index";</script>';

//            return redirect("admin/student/index");
            return view('admin/success')->with([
                //跳转信息
                'message'=>'你已经提交信息，请您耐心等待！',
                //自己的跳转路径
                'url' =>asset('admin/login/login'),
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

    //退出
    public function loginout(Request $request)
    {
        $result=$request->session()->forget('user_name');
//        dd($result);
        if($result==null)
        {
//            echo '<script>alert("注册成功");window.location.href="admin/student/index";</script>';

//            return redirect("admin/student/index");
            return view('admin/success')->with([
                //跳转信息
                'message'=>'退出成功！',
                //自己的跳转路径
                'url' =>asset('admin/login/login'),
                //跳转路径名称
                'urlname' =>'登陆页面',
                //跳转等待时间（s）
                'jumpTime'=>2,
            ]);

        }else{
            echo "<script>alert('退出失败!');history.back();</script>";
//            return redirect("admin/login/register");
        }
    }

}
