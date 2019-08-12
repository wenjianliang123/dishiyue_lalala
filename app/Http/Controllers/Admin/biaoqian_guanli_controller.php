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

        //获取access_token
//        $re=$this->wechat->get_access_token();
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
//        echo $_GET['echostr'];
//        die();
//        echo "您已经进入接口配置的url";
        //        echo 1;dd();
        //$this->checkSignature();

        //php用file_get_contents("php://input")或者$HTTP_RAW_POST_DATA可以接收xml数据
        $data = file_get_contents("php://input");
        dd($data);
        //解析XML LIBXML_NOCDATA - 将 CDATA 设置为文本节点 传过来的数据中带有CDATA样式的前缀 将其设为文本节点
        //将一个XML字符串装载入一个对象中
//        dd($data);
        $xml = simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);        //将 xml字符串 转换成对象
        $xml = (array)$xml; //转化成数组
        //写入日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
//      //file_put_contents — 将一个字符串写入文件 和依次调用 fopen()，fwrite() 以及 fclose() 功能一样。
//        FILE_APPEND	如果文件 filename 已经存在，追加数据而不是覆盖。
        file_put_contents(storage_path('logs/Receive_normal_messages.log'),$log_str,FILE_APPEND);
        \Log::Info(json_encode($xml));
        $message = '您好!';
        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
        echo $xml_str;
    }
    //接收普通消息
    /*
         *  ToUserName	开发者微信号
            FromUserName	发送方帐号（一个OpenID）
            CreateTime	消息创建时间 （整型）
            MsgType	消息类型，文本为text
            Content	文本消息内容
            MsgId	消息id，64位整型
        */
    public function Receive_normal_messages()
    {
//        echo 1;dd();
        //$this->checkSignature();

        //php用file_get_contents("php://input")或者$HTTP_RAW_POST_DATA可以接收xml数据
        $data = file_get_contents("php://input");
        //解析XML LIBXML_NOCDATA - 将 CDATA 设置为文本节点 传过来的数据中带有CDATA样式的前缀 将其设为文本节点
        //将一个XML字符串装载入一个对象中
//        dd($data);
        $xml = simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);        //将 xml字符串 转换成对象
//        dd($xml);
        $xml = (array)$xml; //转化成数组
        //写入日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
//      //file_put_contents — 将一个字符串写入文件 和依次调用 fopen()，fwrite() 以及 fclose() 功能一样。
//        FILE_APPEND	如果文件 filename 已经存在，追加数据而不是覆盖。
        file_put_contents(storage_path('logs/Receive_normal_messages.log'),$log_str,FILE_APPEND);
        \Log::Info(json_encode($xml));
        $message = '您好!';
        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
        echo $xml_str;dd();

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


