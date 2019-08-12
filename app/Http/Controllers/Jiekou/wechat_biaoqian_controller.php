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
    public function Batch_tag_users(Request $request)
    {
        $info=$request->all();
//        dd($info);
        $tag_id=$info['tag_id'];
        $open_id_info=$info['openid_list'];
//                  dd($tag_id,$open_id_info);
        $url="https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=".$this->wechat->get_access_token();

        //dd($open_id_list);
        $data=[
                "openid_list"=>//粉丝列表
                    $open_id_info,
                "tagid"=>$tag_id
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

        $data=$request->all();

        $open_id_info=$data['openid_list'];
        $tag_id=$data['tag_id'];
        $url="https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=".$this->wechat->get_access_token();
//

        //dd($open_id_list);
        $data=[
            "openid_list"=>//粉丝列表
                $open_id_info
            ,
            "tagid"=>$tag_id
        ];
//        dd(json_encode($data));
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }

    //获取用户身上的标签列表
    public function get_user_tag(Request $request)
    {
        $openid=$request->openid;
//        dd($openid);
        $url="https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=".$this->wechat->get_access_token();
        $data=[
            "openid" => $openid
        ];
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $result=json_decode($re,1);


        //以上为该接口   获取用户身上的标签列表  但是只能返回标签id
        //故因此 完善

        //获取id根据id
        $id=$result['tagid_list'];
        $id=implode('',$id);
//        dd($id);
        //问题：怎么传值 解决 调用方法($id)
//        传给获取标签下订蛋用户列表的方法

//        dd($data);
//        dd($id);
        $tag_id=[   "tag_id"=>[$id] ];
        //拿到所有标签
        $result_1=file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=".$this->wechat->get_access_token()."");
        $result_1=json_decode($result_1,1);

        $re_arr = $result_1['tags'];
//        dd($re_arr);
        foreach($re_arr as $v){
            foreach($tag_id['tag_id'] as $vo){
                if($vo == $v['id']){
                    return view('admin/wechat_biaoqian/user_tag_info',['id'=>$vo,'name'=>$v['name'],'openid'=>$openid]);
                }

            }
        }


    }

    //给标签下的用户群发消息
public function Batch_send_tag_user_info(Request $request)
    {
        $info=$request->all();
        $tag_id=$info['tag_id'];
        $content=$info['content'];
//        dd($info);
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=" . $this->wechat->get_access_token();
        $data = [
            "filter" => [
                "is_to_all" => false,
                "tag_id" => $tag_id,
            ],
            "text" =>[
                "content"=>$content
            ],
            "msgtype"=>"text"
        ];
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
        dd($re);
    }

}
