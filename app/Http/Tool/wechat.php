<?php
namespace App\Http\Tool;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class wechat{

    public  $request;
    public  $client;
    public  $app;
    public function __construct(Request $request,Client $client)
    {
        $this->request = $request;
        $this->client = $client;
        $this->app = $app = app('wechat.official_account');
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

    /**
     * 获取JS-SDK中的jsapi_ticket 用于签名
     */
    public function get_jsapi_ticket()
    {
        //实例化一波redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $jsapi_ticket_key = 'jsapi_ticket';
        if($redis->exists($jsapi_ticket_key)){
            //去缓存拿
//            echo '这是缓存';die();
            $jsapi_ticket = $redis->get($jsapi_ticket_key);
        }else{
            //去微信接口拿
            $jsapi_re = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$this->get_access_token()."&type=jsapi");
            $jsapi_result = json_decode($jsapi_re,1);
            $jsapi_ticket = $jsapi_result['ticket'];
            $expire_time = $jsapi_result['expires_in'];
            //加入缓存
            $redis->set($jsapi_ticket_key,$jsapi_ticket,$expire_time);
        }
        return $jsapi_ticket;
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
//        FALSE 禁止 cURL 验证对等证书（peer's certificate）。
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //	设置为 1 是检查服务器SSL证书中是否存在一个公用名(common name)。
//          设置成 2，会检查公用名是否存在，并且是否与提供的主机名匹配。 0 为不检查名称
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //忽略header头信息 	启用时会将头文件的信息作为数据流输出。
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

    //推送模板信息
    public function push_moban_info($open_id,$template_id='yCYPfV_udZC5TM-U_GhCTUgfmQbefMOlGxS-4VfnkxI',$push_user='',$liuyan_content='')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->get_access_token();
        $data = [
            "touser" => $open_id,
            "template_id" => $template_id,
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
                    "value" => "$push_user",//留言发送者
                    "color" => "#173177"
                ],
                "keyword2" => [
                    "value" => "$liuyan_content",//留言内容
                    "color" => "#173177"
                ],
                "remark" => [
                    "value" => "此条消息已完毕！",
                    "color" => "#173177"
                ]
            ]
        ];
        $result=$this->post($url,json_encode($data));
//        dd($result);
        return $open_id;
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

    //非静默授权获取用户信息
    public static function feijingmo_shouquan_get_user_info()
    {
        $openid=session('openid');
        if($openid){
            return $openid;
        }
        $code=request()->get('code');
        if($code){
            $appid="wxd6f4a5c8c232913a";
            $secret="962723e3a8f31ff2102bed4dfbefceac";
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            $re = file_get_contents($url);
            $re=json_decode($re,true);
        // dd($re);
            $access_token=$re['access_token'];
// dd($access_token);
            $openid=$re['openid'];
// dd($openid);
            $url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
// dd($url);
            $info=file_get_contents($url);
            $info=json_decode($info,true);
// dd($info);
            session(['openid'=>$info]);
        }else{
            $host=$_SERVER['HTTP_HOST'];
            $uri=$_SERVER['REQUEST_URI'];
            $appid="wxd6f4a5c8c232913a";
            $redirect_uri=urlencode("http://".$host.$uri);
            $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=111#wechat_redirect";
            header("location:".$url);die;
        }
        return $info;
    }


}




