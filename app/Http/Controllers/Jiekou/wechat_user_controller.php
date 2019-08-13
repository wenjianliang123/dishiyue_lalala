<?php

namespace App\Http\Controllers\Jiekou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Tool\wechat;

class wechat_user_controller extends Controller
{
    public $wechat;
//    public $open_id;
    public function __construct(wechat $wechat)
    {
        $this->wechat =$wechat;
    }

    //测试八月——第一个接口 用的postman辅助判断自定义的access_token
    public function ceshijiekou(Request $request)
    {
        if(empty($request->all()['access_token'])||$request->all()['access_token']!='ACCESS_TOKEN')
        {
            return json_encode(['errno'=>'40014']);
        }
        //这的数据是为了测试用的 通过了access_token验证的话就可以查看此数据
        $info =DB::table('yuekao_xinwen')->get()->toArray();
        $info=json_decode(json_encode($info),1);
        echo json_encode($info);
    }

    //测试八月——获取用户列表、循环入库
    public function get_user_list()
    {
        //$this-》get_access_token方法获取access_token next_openid可以为空  用户不多时可以为空
        $access_token='';
        $access_token=$this->wechat->get_access_token();
//        dd($access_token);
        //file_get_contents是为了实现模拟get请求方式 参数用{$变量名输出}
        $user_list=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=");
//        ！！！需要注意的是 access_token 要将其变为一定时间内不会改变的值 ！！！否则会报40001的错
//                    说你的access_token错误
        $user_list=json_decode($user_list,1);
        //查看时 json_decode 解析一下
//        return $user_list;




        $open_id=$user_list['data']['openid'];
//        $open_id=implode(',',$open_id);
//        $open_id=implode(',',$open_id);
//        dd($open_id);

//        $user_info='';
        $subscribe='';
        foreach ($open_id as $v)
        {
            $user_db_list_info=DB::connection('mysql_shop')->table('wechat_openid')->where('open_id',$v)->value('open_id');
            if(empty($user_db_list_info)){
                $user_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$v}&lang=zh_CN");
                $user_info=json_decode($user_info,1);

                $res=DB::connection('mysql_shop')->table('wechat_openid')->insert([
                    'open_id' => $v,
                    'subscribe' => $user_info['subscribe'],
                    'add_time'=>time(),
                ]);

            }

        }
//
//        echo "<script>history.go(-1)</script>";
        return redirect('wechat/user_list_zhanshi');

    }

    //以上都需去官网中查看文档的接口将参数放进去然后打印查看数据
    //用户列表展示
    public function user_list_zhanshi(Request $request)
    {
        $tag_id=$request->all()['tag_id'];

//        dd($tag_id);
//        $tag_id=implode('',$tag_id);

//        dd($tag_id);
        $user_list_info=DB::connection('mysql_shop')->table('wechat_openid')->get()->toArray();
        return view('dibayue/user_list',['data'=>$user_list_info,'tag_id'=>$tag_id]);
    }

    //用户详情
    public function user_detail($id)
    {
        $info=DB::connection('mysql_shop')->table('wechat_openid')->where('id',$id)->get()->toArray();
        $info=array_map('get_object_vars',$info);
        $info=json_decode(json_encode($info),1);
//        $info=json_decode(json_encode($info));
//        dd($info);
        $open_id=$info[0]['open_id'];
//        dd($open_id);
        $access_token=$this->wechat->get_access_token();
        $user_detail=$this->wechat->get_user_info($open_id);
//        dd($user_detail);
//        dd($user_detail);
        return view('dibayue/user_detail',['data'=>$user_detail,'id'=>$id]);
    }

   //有个网页去跳转、集成在h5项目前台的注册登录
    public function login()
    {
        //首先去配置域名-》测试号->网页帐号	网页授权获取用户基本信息	无上限	修改不要加http等东西 一开始就需要
//        $user_is_empty=DB::connection('mysql_shop')->table('wechat_user')->where('open_id',$openid)->get()->toArray();
        $redirect_uri='http://www.wenjianliang.top/wechat/get_code';
        $url="h ttps://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
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
                'url' =>asset('/'),
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
                'url' =>asset(''),
                //跳转路径名称
                'urlname' =>'首页',
                //跳转等待时间（s）
                'jumpTime'=>3,
            ]);
        }



    }




}
