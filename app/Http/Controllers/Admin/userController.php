<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class userController extends Controller
{
    public function index(Request $request)
    {
        //这里是登陆注册的展示页面
        $redis= new \Redis();
        $redis->connect('127.0.0.1','6379');
        $redis->incr('num2');
        $num1=$redis->get('num2');
//        echo "访问次数为：".$num1."次";
        $user_name='';
        $user_name=$request->get('user_name');//接收到的学生姓名
//        dd($student_name);

        $status=$request->get('status');//

//        dd($student_class);
        $dbstatus=DB::connection('mysql_shop')->table("qiehuan_user")->value("status");//所有权限信息
//        dd($class_info);
//        dd($dbstatus);

        /**
         * 搜索方法1---优化版
         * 1、说明 先声明一个变量where 使其变为空数组
         * 2、
         */
        $where=[];
        if (!empty($user_name)) {
            $where[]=['user_name','like','%'.$user_name.'%'];
        }
        if (!empty($status)) {
            $where[]=['status',$status];
        }
        $data = DB::connection('mysql_shop')->table("qiehuan_user")
            ->where($where)
            ->orderBy('user_id','desc')
            ->paginate(2);
        return view("admin/user/index",['data'=>$data,'user_name'=>$user_name,'dbstatus'=>$dbstatus,'status'=>$status]);
    }

    public function edit($id)
    {
        $data1 = DB::connection('mysql_shop')->table("qiehuan_user")
            ->where('user_id',$id)
            ->first();
//        dd($data);


        return view("admin/user/edit",['data1'=>$data1]);
    }
    public function update(Request $request)
    {
        $data=$request->except(['_token']);
        $user_id=$request->get('user_id');
//        dd($student_id);
//        dd($data);
        $result=DB::connection('mysql_shop')->table("qiehuan_user")->where('user_id',$user_id)->update([
            'user_name'=>$data['user_pwd'],
            'user_pwd'=>$data['user_name'],
            'status'=>$data['status'],
        ]);
        if($result)
        {
            echo "ok";
        }else{
            echo "no";
        }
    }
    public function delete($id)
    {
        $result=DB::connection('mysql_shop')->table("qiehuan_user")->where('user_id',$id)->delete();
        if($result)
        {
//            echo "ok";
//            return redirect('admin/student/index');
//            echo "<script>alert('删除成功!');history.back();</script>";
//            echo "<script>alert('退出成功!');location.href='".$_SERVER[url("admin/user/index")]."';</script>";

            return view('admin/success')->with([
                //跳转信息
                'message'=>'删除成功！',
                //自己的跳转路径
                'url' =>asset('admin/user/index'),
                //跳转路径名称
                'urlname' =>'用户列表',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }else{
//            echo "no";
//            return redirect('admin/student/index');
            echo "<script>alert('删除失败!');history.back();</script>";
        }
    }
}
