<?php

namespace App\Http\Controllers\Jiekou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Tool\wechat;
use DB;

class wechat_biaoqian_controller extends Controller
{
    public $wechat;
    public function __construct(wechat $wechat)
    {
        $this->wechat=$wechat;
    }

    //创建标签
    public function create_biaoqian(Request $request)
    {
        $url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token=".$this->wechat->get_access_token();
        $data=[
            "tag" => ["name"=>$request->all()['name']]
        ];
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }
    //获取公众号已创建的标签
    public function get_tag()
    {
        $re=file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->wechat->get_access_token()."");
        $re=json_decode($re,1);
        dd($re);
    }
    //编辑标签
    public function edit_tag(Request $request)
    {
//        dd();
        $name=$request->all(['name']);

        $name=implode('',$name);

        $id=$request->all(['tag_id']);
        $id=implode('',$id);

//        dd($name);
        $url="https://api.weixin.qq.com/cgi-bin/tags/update?access_token=".$this->wechat->get_access_token();
        $data=[
            "tag" => [ "id"=>$id ,"name"=>$name]
        ];
//        dd($data);
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }
    //删除标签
    /**
     * 请注意，当某个标签下的粉丝超过10w时，后台不可直接删除标签。
     * 此时，开发者可以对该标签下的openid列表，先进行取消标签的操作，直到粉丝数不超过10w后，才可直接删除该标签。
    */
    public function delete_tag(Request $request)
    {
//        dd();
        $url="https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=".$this->wechat->get_access_token();
        $data=[
            "tag" => [ "id"=>intval($request->id)]
        ];
//        dd($data);
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }
    //批量为用户打标签 batch批量
    public function Batch_tag_users()
    {
        $url="https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=".$this->wechat->get_access_token();
        $open_id_info=DB::connection('mysql_shop')->table('wechat_openid')->select('open_id')->get()->toArray();
        $open_id_list=[];
//        dd($open_id_info);
        foreach ($open_id_info as $v)
        {
            $open_id_list[]=$v->open_id;
//            dump($open_id_list);
        }
        //dd($open_id_list);
        $data=[
                "openid_list"=>//粉丝列表
                    $open_id_list
                    ,
                "tagid"=>104
                ];
//        dd(json_encode($data));
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }

    //5. 获取标签下粉丝列表
    public function get_tag_user($id)
    {
//        dd($request->id);
        $url="https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=".$this->wechat->get_access_token();
        $data=[
            "tagid" => $id,"next_openid"=>""
        ];
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        return $re;
    }

    //批量为用户取消标签
    public function Batch_tag_user_delete(Request $request)
    {
//        dd(1);

        $all=$request->all();
        dd($all);
//        $id=$request->tag_id;
//        $openid=$request->openid;
//        dd($id,$openid);
        $url="https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=".$this->wechat->get_access_token();
        $open_id_info=DB::connection('mysql_shop')->table('wechat_openid')->select('open_id')->get()->toArray();
        $open_id_list=[];
//        dd($open_id_info);
        foreach ($open_id_info as $v)
        {
            $open_id_list[]=$v->open_id;
//            dump($open_id_list);
        }
        //dd($open_id_list);
        $data=[
            "openid_list"=>//粉丝列表
                $open_id_list
            ,
            "tagid"=>104
        ];
//        dd(json_encode($data));
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }

    //获取用户身上的标签列表
    public function get_user_tag()
    {
        $url="https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=".$this->wechat->get_access_token();
        $data=[
            "openid" => "oMbARt6tCM2dJZL6MjdKPmOxrpMY"
        ];
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }

}
