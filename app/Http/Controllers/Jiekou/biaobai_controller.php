<?php

namespace App\Http\Controllers\Jiekou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use DB;
use App\Http\Tool\wechat;

class biaobai_controller extends Controller
{

    public $wechat;
    public function __construct(wechat $wechat)
    {
        $this->wechat=$wechat;
    }

    //需要用到微信开发工具
    public function login()
    {
        //首先去配置域名-》测试号->网页帐号	网页授权获取用户基本信息	无上限	修改不要加http等东西 一开始就需要
//        $user_is_empty=DB::connection('mysql_shop')->table('wechat_user')->where('open_id',$openid)->get()->toArray();
        $redirect_uri='http://www.dishiyue.com/biaobai/get_code';
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
            session(['wechat_user_name' =>$user_wechat_info->name]);
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
                'url' =>asset('/biaobai/user_list'),
                //跳转路径名称
                'urlname' =>'表白用户列表',
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
            session(['wechat_user_name'=>$user_wechat_info['name']]);
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
                'url' =>asset('/biaobai/user_list'),
                //跳转路径名称
                'urlname' =>'表白用户列表',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }



    }


    //获取用户列表
    public function get_user_list()
    {
        $data=DB::connection('mysql_shop')->table('user_wechat')->get()->toArray();
        $data=json_decode(json_encode($data),1);
//        dd($data);
        return view("wechat/biaobai/biaobai_list",['data'=>$data]);

    }
    //填写表白内容的视图
    public function biaobai_content_view(Request $request)
    {
        $user_id=$request->id;
        $open_id=DB::connection('mysql_shop')->table('wechat_user')->where('user_id',$user_id)->select('open_id')->get()->toarray();
        $open_id=json_decode(json_encode($open_id),1);
        $open_id=implode('',$open_id[0]);
//        dd($open_id);
        return view("wechat/biaobai/biaobai_content_view",['open_id'=>$open_id]);
    }
    //推送表白内容
    public function push_biaobai(Request $request)
    {
        $data=$request->all();
//        dd($data);

        $user_name=$this->wechat->get_user_info($data['open_id'])['nickname'];
//        dd($user_name);
        $push_user=session('wechat_user_name');
//        dd($push_user);
        $moban=$this->wechat->push_moban_info($data['open_id'],'4m-PXL3AnNxJZRZUCF1US--1Ps08V0IL8RIOyoUy9AA',$data['biaobai_leixing']==1?$push_user:'匿名用户',$data['biaobai_content']);
//
        if($moban){
            $db_info=DB::Connection('mysql_shop')->table('wechat_biaobai')->insert([
                'push_user'=>$push_user,
                'user_name'=>$user_name,
                'biaobai_content'=>$data['biaobai_content'],
                'add_time'=>time(),
            ]);
            if($db_info){
                dd('表白成功');
            }
        }

    }

    public function my_biaobai()
    {
        $user_name=session('wechat_user_name');
        $user_info=DB::connection('mysql_shop')->table('wechat_biaobai')->where('user_name',$user_name)->get()->toarray();
//        dd($user_info);
    }
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function test()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        // 自己写的 获取用户信息
        $user = $app->user->get('oMbARt6tCM2dJZL6MjdKPmOxrpMY');
//        dd($user);
        //系统展示代码是否能够使用
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();

        //测试油价截取
//        $city="吐鲁番油价";
//        $sub=substr($city,0,-6);
//        dd($sub);
    }
}
