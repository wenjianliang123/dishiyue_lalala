<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Tool\wechat;
use App\Http\Controllers\Jiekou\wechat_biaoqian_controller;
use DB;

class biaoqian_guanli_controller extends Controller
{

    public $wechat;
    public $wechat_biaoqian_controller;
    public function __construct(wechat $wechat,wechat_biaoqian_controller $wechat_biaoqian_controller)
    {
        $this->wechat = $wechat;
        $this->wechat_biaoqian_controller = $wechat_biaoqian_controller;
    }

    //获取标签列表 可优化
    public function biaoqian_list()
    {
        //调用清零接口次数限制
//        $re=$this->wechat->empty_api_count();
//        dd($re);

        $re=file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->wechat->get_access_token()."");
        $re=json_decode($re,1);
//        dd($re);
        return view("admin/wechat_biaoqian/biaoqian_list_1",['data'=>$re]);
    }
    //创建标签的视图
    public function create_biaoqian_view(){
        return view('admin/wechat_biaoqian/create_biaoqian_view');
    }
    //修改标签的视图  --可优化
    public function edit_biaoqian_view(Request $request){

//        可行思路 拿到标签id 两次foreach 一个所有标签列表 一个只有标签id 但是需要转化
//            如果标签列表的id等于发过来的标签id 输出到视图name
        $id=$request->id;
//        dd($id);
        $tag_id=[   "tag_id"=>[$id] ];
        //拿到所有标签
        $re=file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->wechat->get_access_token()."");
        $re=json_decode($re,1);

        $re_arr = $re['tags'];
//        dd($re_arr);
        foreach($re_arr as $v){
            foreach($tag_id['tag_id'] as $vo){
                if($vo == $v['id']){
                    return view('admin/wechat_biaoqian/eidt_biaoqian_view',['id'=>$vo,'name'=>$v['name']]);
                }
            }
        }

//
    }

    //获取标签下用户列表
    public function get_tag_user_view(Request $request)
    {
        //获取id根据id
        $id=$request->id;
        //问题：怎么传值 解决 调用方法($id)
//        传给获取标签下订蛋用户列表的方法
        $data=$this->wechat_biaoqian_controller->get_tag_user($id);
//        dd($data);
//        dd($id);
        $tag_id=[   "tag_id"=>[$id] ];
        //拿到所有标签
        $re=file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->wechat->get_access_token()."");
        $re=json_decode($re,1);

        $re_arr = $re['tags'];
//        dd($re_arr);
        foreach($re_arr as $v){
            foreach($tag_id['tag_id'] as $vo){
                if($vo == $v['id']){
                    return view('admin/wechat_biaoqian/tag_user_list',['data'=>$data,'id'=>$vo,'name'=>$v['name']]);
                }
            }
        }



    }

    //根据标签群发消息
    public function Batch_send_tag_user_info_view(Request $request)
    {
        $tag_id=implode($request->all());
//        dd($tag_id);
        return view('admin/wechat_biaoqian/Batch_send_tag_user_info_view',['tag_id'=>$tag_id]);
    }

    //接口配置的url
    public function jiekou_peizhi_url()
    {
        // （只能用于线上）第一次无法配置成功使用以下两行代码
        /*echo $_GET['echostr'];
        die();*/
        echo "您已经进入接口配置的url";
    }
}


