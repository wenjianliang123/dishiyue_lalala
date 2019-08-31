<?php

namespace App\Http\Controllers\Dijiuyue;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Log;
use App\Http\Tool\wechat;

class student_controller extends Controller
{

    public $wechat;
    public function __construct(wechat $wechat)
    {
        $this->wechat=$wechat;
    }
    //登录 CURD 分页 搜索

    //学生添加
    public function student_add_view()
    {
        $data=DB::table('class')->get()->toarray();
        $data=json_decode(json_encode($data),1);
//        dd($data);
        return view('admin/jiuyue/student/student_add',['data'=>$data]);
    }
    //执行添加
    public function student_do_add(Request $request)
    {
        $data=$request->all();
//        dd($data);
        $res=DB::table('student_1')->insert([
            'student_name'=>$data['student_name'],
            'student_sex'=>$data['student_sex'],
        ]);
        if ($res)
        {
            echo '添加成功';
            return redirect('jiuyue/student/student_list');
        }else{
            echo "<script>alert('添加失败!');history.back();</script>";
        }
    }
    //学生列表
    public function index(Request $request)
    {

        $student_name=$request->get('student_name');//接收到的学生姓名
        $student_sex=$request->get('student_sex');//接收到的班级信息
        $where=[];
        if (!empty($student_name)) {
            $where[]=['student_name','like','%'.$student_name.'%'];
        }
        if (!empty($student_sex)) {
            $where[]=['student_sex',$student_sex];
        }
        $data = DB::table('student_1')
            ->where($where)
            ->paginate(2);
        return view("admin/jiuyue/student/student_list",['data'=>$data,'student_name'=>$student_name,'student_sex'=>$student_sex]);
    }
    //编辑页面
    public function edit($id)
    {

        $data = DB::table('student_1')
            ->where('student_id','=',$id)
            ->get();
//        dd($data);
        $data_1=[];
        $data_1=json_decode(json_encode($data),1)[0];
//        dd($data_1);
        return view("/admin/jiuyue/student/student_edit",['data'=>$data_1]);
    }
    //执行页面
    public function update(Request $request)
    {
        $data=$request->except(['_token']);
        $student_id=$request->get('student_id');
        $result=DB::table("student_1")->where('student_id',$student_id)->update([
            'student_name'=>$data['student_name'],
            'student_sex'=>$data['student_sex'],
        ]);
        if($result)
        {
            echo "ok";
        }else{
            echo "no";
        }
    }
    //删除
    public function delete($id)
    {
        $result=DB::table("student_1")->where('student_id',$id)->delete();
        if($result)
        {
            echo "<script>alert('删除成功!');history.back();</script>";
        }else{
            echo "<script>alert('删除失败!');history.back();</script>";
        }
    }



    //需要用到微信开发工具
    public function login()
    {
        //首先去配置域名-》测试号->网页帐号	网页授权获取用户基本信息	无上限	修改不要加http等东西 一开始就需要
//        $user_is_empty=DB::connection('mysql_shop')->table('wechat_user')->where('open_id',$openid)->get()->toArray();
        $redirect_uri='http://www.dishiyue.com/jiuyue/student/get_code';
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header('Location:'.$url);
    }
    //访问获取code 的接口获取ocde、集成在h5项目前台的注册登录
    public function get_code(Request $request)
    {
        $data=$request->all();
//        dd($data);
        $code=$data['code'];

        //根据code 获取access_token（这里的token和获取用户信息的token的不一样）
        $url=file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."&code={$code}&grant_type=authorization_code");
//        dd($url);
        $url=json_decode($url,1);
//        dd($url);
        $access_token=$url['access_token'];
        $openid=$url['openid'];
//        dd($access_token);
//        dd($openid);

        //根绝获取到的token去调用获取用户信息的接口  ---可用 但要用下面更简单的
//        $user_info=file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN");
//        $user_info=json_decode($user_info,1);
//        dd($user_info);

        //调用封装好的方法获取用户信息
        $user_info=$this->wechat->get_user_info($openid);
//        dd($user_info);

        //检测access_token是否有效
//        $is_ture=file_get_contents("https://api.weixin.qq.com/sns/auth?access_token={$access_token}&openid={$openid}");
//        $is_ture=json_decode($is_ture,1);
//        dd($is_ture);

        //去根据openid判断用户微信关系表是否有该用户
        $wechat_user_info=DB::connection('mysql_shop')->table('wechat_user')->where('open_id',$openid)->first();
//        dd($wechat_user_info);

        $wechat_user_info=json_decode(json_encode($wechat_user_info),1);
        if(!empty($wechat_user_info)){
            //有这个用户进行登录操作
//            echo "这是登陆";
            $user_wechat_info=DB::connection('mysql_shop')->table("user_wechat")->where(['id'=>$wechat_user_info['user_id']])->first();
//            dd($request->session()->put('username',$user_wechat_info->name));
//            dd($user_wechat_info->name);
            session(['jiuyue_student_user_name' =>$user_wechat_info->name]);
            $template_id="JvdX4By15TD56N8Z-kS3XWRcVt2EeO9Bh98wKgCvldQ";
            $this->wechat->push_moban_info($openid,$template_id);
//            dd(session('wechat_user_name'));
//            if($session_add){
//                echo '登录成功';die;
//            }
//            dd($session_add);
//            echo '登录成功';die;
//            $redirect_url=url('www.dishiyue.com');
            return view('admin/success')->with([
                //跳转信息
                'message'=>'登陆成功 正在跳转至表白用户列表！',
                //自己的跳转路径
                'url' =>asset('/jiuyue/student/student_list'),
                //跳转路径名称
                'urlname' =>'学生列表',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }else{
            //没有这个用户进行注册并且登陆操作
            //insert user_wechat  wechat_user  生成新用户
            DB::connection("mysql_shop")->beginTransaction();
//            echo "这是注册并登录";
            $user_wechat_add=DB::connection('mysql_shop')->table('user_wechat')->insertGetID([
                'name'=>$user_info['nickname'],
                'password'=>'',
                'register_time'=>time(),
            ]);
            $wechat_user_add=DB::connection('mysql_shop')->table('wechat_user')->insert([
                'user_id'=>$user_wechat_add,
                'open_id'=>$openid,
            ]);

            DB::connection('mysql_shop')->commit();

            //登陆操作
            $user_wechat_info=DB::connection('mysql_shop')->table("user_wechat")->where(['id'=>$wechat_user_info['user_id']])->first();
//            dd($request->session()->put('wechat_user_name',$user_wechat_info['name']));
//            dd($user_wechat_info->name);
            $user_wechat_info=json_decode(json_encode($user_wechat_info),1);
            session(['jiuyue_student_user_name'=>$user_wechat_info['name']]);
            $template_id="JvdX4By15TD56N8Z-kS3XWRcVt2EeO9Bh98wKgCvldQ";
            $this->wechat->push_moban_info($openid,$template_id);
//            dd(session('wechat_user_name'));
//            if($session_add){
//                echo '注册登录成功';die;
//            }
//            dd($session_add);
//            echo '注册登录成功';die;
//            $redirect_url=url('www.dishiyue.com');
            return view('admin/success')->with([
                //跳转信息
                'message'=>'登陆成功 正在跳转至表白用户列表！',
                //自己的跳转路径
                'url' =>asset('/jiuyue/student/student_list'),
                //跳转路径名称
                'urlname' =>'学生列表',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }



    }

}
