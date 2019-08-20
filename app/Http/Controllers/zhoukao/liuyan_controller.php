<?php

namespace App\Http\Controllers\zhoukao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Jiekou\wechat_moban_controller;
use App\Http\Tool\wechat;
use DB;

class liuyan_controller extends Controller
{
    public $wechat_moban_controller;
    public $wechat;
//    public $open_id;
    public function __construct(wechat_moban_controller $wechat_moban_controller,wechat $wechat)
    {
        $this->wechat_moban_controller =$wechat_moban_controller;
        $this->wechat =$wechat;
    }
    //从数据库中拿到用户信息
    public function get_user_list()
    {
        $user_list_info=DB::connection('mysql_shop')->table('user_wechat')->get()->toArray();
        $user_list_info=json_decode(json_encode($user_list_info),1);
        return view('zhoukao/liuyan/user_list',['data'=>$user_list_info]);
    }

    //需要用到微信开发工具
    public function login()
    {
        //首先去配置域名-》测试号->网页帐号	网页授权获取用户基本信息	无上限	修改不要加http等东西 一开始就需要
//        $user_is_empty=DB::connection('mysql_shop')->table('wechat_user')->where('open_id',$openid)->get()->toArray();
        $redirect_uri='http://www.dishiyue.com/zhoukao/liuyan/get_code';
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
                'message'=>'登陆成功，正在跳转至首页！',
                //自己的跳转路径
                'url' =>asset('/zhoukao/liuyan/get_user_list'),
                //跳转路径名称
                'urlname' =>'首页',
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
                'message'=>'注册、登陆成功，正在跳转至首页！',
                //自己的跳转路径
                'url' =>asset('/zhoukao/liuyan/get_user_list'),
                //跳转路径名称
                'urlname' =>'用户列表',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }



    }

    //推送消息的页面
    public function push_liuyan_view(Request $request){
        $id=$request->id;
//        dd($id);
        return view('zhoukao/liuyan/push_liuyan_view',['user_id'=>$id]);
    }
    //留言 ———推送消息
    public function push_liuyan(Request $request)
    {
        //用户id
        $id=$request->user_id;
        //留言内容
        $liuyan_content=$request->liuyan_content;
//        dd($liuyan_content);
//        dd($id);
        $wechat_user_info=DB::connection('mysql_shop')->table('wechat_user')->where('user_id',$id)->select('open_id')->get()->toArray();
        $wechat_user_info=json_decode(json_encode($wechat_user_info),1);
        $open_id=$wechat_user_info[0]['open_id'];
//        dd($open_id);
        //模板
        $template_id="B4Bxv_jmNERAXWgHWGyK_rlM-b6t9EF-VABWMyBREPE";
        //发送者
        $push_user=session('wechat_user_name');
        //返回来的是一个用户的openid
        $pull_openid=$this->wechat->push_moban_info($open_id,$template_id,$push_user,$liuyan_content);
//        dd($pull_openid);
//        ,['pull_openid'=>$pull_openid]
        if(!empty($pull_openid))
        {
            $user_name=$this->wechat->get_user_info($pull_openid)['nickname'];
//            url('wechat/user_list_zhanshi')}}?tag_id={{$v['id']
            $ruku=DB::connection('mysql_shop')->table('wechat_liuyan')->insert([
                'push_user'=>$push_user,
                'user_name'=>$user_name,
                'liuyan_content'=>$liuyan_content,
                'open_id'=>$pull_openid,
                'add_time'=>time(),
            ]);
            if($ruku)
            {
//                return redirect("zhoukao/liuyan/my_liuyan"?'open_id':$open_id);
                return redirect("zhoukao/liuyan/my_liuyan?pull_openid=$pull_openid");
            }else{
                echo "留言入库失败";
            }
        }else{
            echo "推送失败";die();
        }
    }
    //我的留言
    public function my_liuyan(Request $request)
    {
        $pull_openid=$request->all()['pull_openid'];
//        dd($pull_openid);
        $data=DB::connection('mysql_shop')->table('wechat_liuyan')->where('open_id',$pull_openid)->orderBy('liuyan_id','desc')->first();
//        $data=array_map('get_object_vars',$data);
        $data=json_decode(json_encode($data),1);
//        dd($data);
        return view('zhoukao/liuyan/my_liuyan',['data'=>$data]);
    }

    //测试
    public function test()
    {
        $re=$this->wechat->get_jsapi_ticket();
        dd($re);
    }



}
