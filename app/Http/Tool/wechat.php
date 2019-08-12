<?php
namespace App\Http\Tool;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class wechat{

    public  $request;
    public  $client;
    public function __construct(Request $request,Client $client)
    {
        $this->request = $request;
        $this->client = $client;
    }
    //测试八月——获取用户信息
    public function get_user_info($open_id)
    {
        //从封装的地方拿access_token
        $access_token=$this->get_access_token();
        //用户的openid是从用户列表中拿到的

        //根据用户openid和access_token调用接口查询
        $user_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$open_id}&lang=zh_CN");
        $user_info=json_decode($user_info,1);
        return $user_info;
    }

    //测试八月——单独将access_token封装起来 成为一个方法
    //！！！需要注意的是 access_token 要将其变为一定时间内不会改变的值 ！！！否则会报40001的错
    public function get_access_token()
    {
        $access_token_key='wechat_access_token';
        $redis=new \Redis();
        $redis->connect('127.0.0.1','6379');

        //在方法中判断key
        if($redis->exists($access_token_key))
        {
            //从缓存中拿access_token
            $access_token=$redis->get($access_token_key);
//            echo '这是从缓存中拿到的access_token';
//            dd($access_token);
        }else{
            //如果没有 调用接口拿access_token 并存入redis
            $access_token_info=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."");
            $access_token_info=json_decode($access_token_info,1);
//            dd($access_token_info);
            //数组的操作需要json_decode($data,1)变为关联数组
            $access_token=$access_token_info['access_token'];
            $expires_in=$access_token_info['expires_in'];
            $redis->set($access_token_key,$access_token,$expires_in);
        }
        //最终返回一个access_token
        return $access_token;
    }

    //curl发送post请求
    public function post($url, $data){
        //初使化init方法
        $ch = curl_init();
        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送什么数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //发送请求
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }

    //推送
    public function push_moban_info($open_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->get_access_token();
        $data = [
            "touser" => $open_id,
            "template_id" => "wE_3J32otV-XecF9rgGLavjaSwgJDecndSI56owqaio",
            "url" => "http://weixin.qq.com/download",
            //跳转小程序需要下面的miniprogram的参数
//                    "miniprogram"=>[
//                        "appid"=>"xiaochengxuappid12345",
//                        "pagepath"=>"index?foo=bar"
//                    ],
            "data" => [
                "first" => [
                    "value" => "这是first的消息！",
                    "color" => "#173177"
                ],
                "keyword1" => [
                    "value" => "对",
                    "color" => "#173177"
                ],
                "keyword2" => [
                    "value" => "错",
                    "color" => "#173177"
                ],
                "remark" => [
                    "value" => "此条消息已完毕！",
                    "color" => "#173177"
                ]
            ]
        ];
        $result=$this->post($url,json_encode($data));
    }

    /**
     * 上传微信素材资源
     * uo_type 1为临时 2为永久
     * type 文件类型 image voice video thumb
     * title 视频标题
     * desc 视频描述
     *
     *  media是键
     *  name和contents是规范必须这么写但不是我们所用的键和值
     *  fopen(realpath($path),1)是值
     *  fopen()文件函数 打开文件
     *  realpath---返回相对于当前操作系统的绝对路径
     *  mode:r 只读
     *
     *  /**
     * 上传资源
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException

     */
    public function upload_source($up_type,$type,$title='',$desc=''){
        $file = $this->request->file($type);
//        dd($file);//显示上传的文件
        $file_ext = $file->getClientOriginalExtension();  //获取文件扩展名
        //重命名
        $new_file_name = time().rand(1000,9999). '.'.$file_ext;
//        dd($new_file_name);
        //文件保存路径
        //保存文件
        //自己：storeaAs是为了重命名
        $save_file_path = $file->storeAs('wechat/video',$new_file_name); //返回保存成功之后的文件路径
//        dd($save_file_path);
        //根据当前项目的绝对路径 可能是
        $path = './storage/'.$save_file_path;
//        dd($path);
        //判断临时还是永久 1为临时 2为永久
        if($up_type  == 1){
            $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->get_access_token().'&type='.$type;
        }elseif($up_type == 2){
            $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->get_access_token().'&type='.$type;
        }
        //普通的上传
        $multipart = [
            [
                'name'     => 'media',
                'contents' => fopen(realpath($path), 'r')
            ],
        ];
        //视频需要另一个post表单 详见文档https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738729
        /**
         * 新增永久视频素材需特别注意

        在上传视频素材时需要POST另一个表单，id为description，包含素材的描述信息，内容格式为JSON，格式如下：

            {
            "title":VIDEO_TITLE,
            "introduction":INTRODUCTION
            }
        */
        if($type == 'video' && $up_type == 2){
            $multipart[] = [
                'name'     => 'description',
                'contents' => json_encode(['title'=>$title,'introduction'=>$desc])
            ];
        }
        $response = $this->client->request('POST',$url,[
            'multipart' => $multipart
        ]);
        //返回信息
        $body = $response->getBody();
        unlink($path);
        return $body;
    }

    //清零调用接口次数限制
    public function empty_api_count()
    {
        $url="https://api.weixin.qq.com/cgi-bin/clear_quota?access_token=".$this->get_access_token();
        $data=[
            "appid"=>env('WECHAT_APPID')
        ];
        $re=$this->post($url,json_encode($data));
        dd($re);

    }
}




