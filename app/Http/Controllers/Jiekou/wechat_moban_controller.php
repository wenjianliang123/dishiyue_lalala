<?php

namespace App\Http\Controllers\Jiekou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Http\Tool\wechat;

class wechat_moban_controller extends Controller
{
    public $wechat;
//    public $open_id;
    public function __construct(wechat $wechat)
    {
        $this->wechat =$wechat;
    }
    //获得模板ID post请求  此功能不能使用
    // //需要开启curl扩展 并且重新监听cgi 否则不能使用
    public function get_moban_id()
    {
        $url="https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=".$this->wechat->get_access_token();
        $data=[
            "template_id_short"=>"TM00015"
        ];
        $result=$this->wechat->post($url,$data);
        dd($result);//报错 系统错误 初步判断：缺少模板编号
    }
    //获取模板列表
    public function get_moban_list()
    {
        $moban_list_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=".$this->wechat->get_access_token());
        $moban_list_info=json_decode($moban_list_info,1);
        dd($moban_list_info);
    }

    //删除模板 需要开启curl扩展 并且重新监听cgi 否则不能使用
    public function delete_moban()
    {
        $url="https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=".$this->wechat->get_access_token();
        $data=[
            'template_id'=>'x0ZLitBaVfFNWwiLsFEL103gSYtbi0yk-FdD-MOW52E',
        ];
        $result=$this->wechat->post($url,json_encode($data));
        dd($result);
    }
    //推送模板信息 需要开启curl扩展 并且重新监听cgi 否则不能使用
    public function push_moban_info()
    {
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->wechat->get_access_token();
        $openid_info=DB::connection('mysql_shop')->table('wechat_openid')->select('open_id')->get();
//        dd($openid_info);
        foreach ($openid_info as $v) {
//            dump($v);
                $this->wechat->push_moban_info($v->open_id);
        };
//        dd();
//        $result=$this->wechat->post($url,json_encode($data));
//        dd($result);
    }




}
