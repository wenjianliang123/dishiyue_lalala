<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Tool\wechat;

class wechat_moban_controller extends Controller
{
    public $wechat;
    public function __construct(wechat $wechat)
    {
        $this->wechat=$wechat;
    }
    //在项目后台添加模板信息管理的列表
    //根据参数查询模板列表
    //删除按钮带上模板id
    //删除时根据模板id删除
    public function moban_list()
    {
        $moban_list_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=".$this->wechat->get_access_token());
        $moban_list_info=json_decode($moban_list_info,1);
//        dd($moban_list_info);
//        $num=1;
//        $num+=1;
//        dd($num);
        return view('admin/wechat_moban/moban_list',['moban_list_info'=>$moban_list_info,]);
    }
    public function del_moban(Request $request)
    {
        $data=$request->all();
        $url="https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=".$this->wechat->get_access_token();
        $result=$this->wechat->post($url,json_encode($data));
        if($result){
            echo json_encode(['font'=>'模板删除成功','code'=>1],JSON_UNESCAPED_UNICODE);exit;
        }else{
            echo json_encode(['font'=>'模板删除失败','code'=>2],JSON_UNESCAPED_UNICODE);exit;
        }
    }

}
