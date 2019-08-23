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


    public function get_user_list()
    {
        $data=DB::connection('mysql_shop')->table('user_wechat')->get()->toArray();
        $data=json_decode(json_encode($data),1);
//        dd($data);
        return view("wechat/biaobai/biaobai_list",['data'=>$data]);

    }
    public function biaobai_content_view(Request $request)
    {
        $user_id=$request->id;
        $open_id=DB::connection('mysql_shop')->table('wechat_user')->where('user_id',$user_id)->select('open_id')->get()->toarray();
        $open_id=json_decode(json_encode($open_id),1);
        $open_id=implode('',$open_id[0]);
//        dd($open_id);
        return view("wechat/biaobai/biaobai_content_view",['open_id'=>$open_id]);
    }

    public function push_biaobai(Request $request)
    {
        $data=$request->all();
//        dd($data);
        $user_info=$this->wechat->get_user_info($data['open_id']);
        $user_name=$user_info['nickname'];
//        dd($user_name);

        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->wechat->get_access_token();
        $data='';
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
        //获取用户信息
        $user = $app->user->get('oMbARt6tCM2dJZL6MjdKPmOxrpMY');
//        dd($user);
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();
    }
}
