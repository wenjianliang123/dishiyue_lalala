<?php

namespace App\Http\Controllers\Yuekao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class xinwenController extends Controller
{
    public function login()
    {
        return view('yuekao/login');
    }
    public function do_login(Request $request)
    {
        $data=$request->all();
//        dd($data);
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
        session(['user_info'=>$data]);
        return redirect('yuekao/xinwen/index');
    }
    public function xinwen_add()
    {
        return view('yuekao/xinwen_add');
    }
    public function xinwen_do_add(Request $request)
    {
        $data=$request->all();
//        dd($data);
        if($request->file('xinwen_picture')=='')
        {
            $res=DB::table('yuekao_xinwen')->insert([
                'xinwen_name'=>$data['xinwen_name'],
                'xinwen_zuozhe'=>$data['xinwen_zuozhe'],
                'xinwen_detail'=>$data['xinwen_zuozhe'],
                'add_time'=>$data['add_time'],
                'user_name'=>session('user_info')['user_name'],
            ]);
            if ($res){
                return redirect('/yuekao/xinwen/index');
            }
        }else{
            $path=$request->file('xinwen_picture')->store('/xinwen_picture');
            $path_url='storage/'.$path;
            $data['xinwen_picture']=$path_url;

//        dd($data);
            $res=DB::table('yuekao_xinwen')->insert([
                'xinwen_name'=>$data['xinwen_name'],
                'xinwen_zuozhe'=>$data['xinwen_zuozhe'],
                'xinwen_detail'=>$data['xinwen_detail'],
                'xinwen_picture'=>$data['xinwen_picture'],
                'add_time'=>time(),
                'user_name'=>session('user_info')['user_name'],
            ]);
            if ($res){
                return redirect('yuekao/xinwen/index');
            }
        }
    }
    public function index()
    {
        $data=DB::table('yuekao_xinwen')->orderBy('xinwen_id','desc')->paginate(2);
//        dd($data);
        return view('yuekao/xinwen_index',['data'=>$data]);
    }
    public function del($id)
    {
        $user_name=Session('user_info')['user_name'];

        $db_info=DB::table('yuekao_xinwen')->where('xinwen_id',$id)->select('add_time','user_name')->first();

        if($user_name!=$db_info->user_name){
            echo "<script>alert('您只能删除自己添加的新闻');history.back(0)</script>";die;
        }else{
            if(time()-$db_info->add_time>1800){
                echo "删除时间已超时,不能删除，您只能删除半小时内的数据";die;
            }else{
                $res=DB::table('yuekao_xinwen')->where('xinwen_id',$id)->delete();
            }


        }

        if($res)
        {
            return redirect('yuekao/xinwen/index');
        }else{
            echo "<script>alert('删除失败');history.back(0)</script>";die;
        }

    }
    public function xinwen_detail($id)
    {
        $redis= new \Redis();
        $redis->connect('127.0.0.1','6379');
        //访问量
        $key1=$id.'1';
        $redis->incr($key1);
        $xinwen=$redis->get($key1);

        //缓存优化
        $key = $id;
        if($redis->exists($key)){
            $redis_info = $redis->get($key);
            $info = json_decode($redis_info,1);
            echo "这是从缓存中拿的";
        }else{
            $info = DB::table('yuekao_xinwen')->where('xinwen_id',$id)->get()->toarray();
            $info = json_decode(json_encode($info),1);
            $redis->set($key,json_encode($info),180);
        }

        return view('/yuekao/xinwen_detail',['data'=>$info,'fangwen'=>$xinwen]);
    }






}
