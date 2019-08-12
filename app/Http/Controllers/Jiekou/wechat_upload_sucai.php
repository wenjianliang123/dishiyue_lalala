<?php

namespace App\Http\Controllers\Jiekou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Tool\wechat;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class wechat_upload_sucai extends Controller
{
    public $wecaht;
    public function __construct(wechat $wechat)
    {
        $this->wechat=$wechat;
    }

    //上传永久和临时的素材视图
    public function upload_sucai()
    {
//        $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$this->wechat->get_access_token()."&type=TYPE";
//        $data=[];
        return view('wechat.upload_sucai');
    }

    //上传临时永久都可以 已优化
    public function do_upload_sucai(Request $request)
    {
//        $aa=$request->all();
//        dd($aa);

        $upload_type = $request['up_type'];
        $re = '';
        if($request->hasFile('image')){
            //图片类型
            $re = $this->wechat->upload_source($upload_type,'image');
        }elseif($request->hasFile('voice')){
            //音频类型
            //保存文件
            $re = $this->wechat->upload_source($upload_type,'voice');
        }elseif($request->hasFile('video')){
            //视频
            //保存文件
            $re = $this->wechat->upload_source($upload_type,'video','视频标题','视频描述');
        }elseif($request->hasFile('thumb')){
            //缩略图 和图片一样 所以没处理
            $path = $request->file('thumb')->store('wechat/thumb');
        }
        echo $re;
        dd();
    }


    //获取（永久图片）素材列表
    public function get_yongjiu_image_sucai_list()
    {
        $url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->wechat->get_access_token();
        $data=[
            "type"=>'image',
            "offset"=>0,
            "count"=>20
        ];
        $re=$this->wechat->post($url,json_encode($data));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }

    //获取（永久音频）素材列表
    public function get_yongjiu_voice_sucai_list()
    {
        $url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->wechat->get_access_token();
        $data=[
            "type"=>'voice',
            "offset"=>0,
            "count"=>20
        ];
        $re=$this->wechat->post($url,json_encode($data));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }

    //获取（永久视频）素材列表
    public function get_yongjiu_video_sucai_list()
    {
        $url="https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$this->wechat->get_access_token();
        $data=[
            "type"=>'video',
            "offset"=>0,
            "count"=>20
        ];
        $re=$this->wechat->post($url,json_encode($data));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }


    //删除永久素材 图片视频音频都可以
    public function delete_yongjiu_sucai()
    {
        $url="https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=".$this->wechat->get_access_token();
        $data=[
            "media_id"=>'KumWbj3jWGdSLPnLkQROINd6QfOHBj2WeCo7_OYm2Hk'//每用一次就需要换一次
        ];
        $re=$this->wechat->post($url,json_encode($data));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }

    //获取素材总数 下面的数量不用写
    public function get_sucai_count()
    {
        $url="https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=".$this->wechat->get_access_token();
        $data=[
            "voice_count"=>'',
            "video_count"=>'',
            "image_count"=>'',
            "news_count"=>''
        ];
        $re=$this->wechat->post($url,json_encode($data));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }


    //获取临时图片素材（普通）
    public function get_image_source()
    {
        //拿到一个素材id
        $media_id = 'XKg1l2ZQgrpjqJln6oxDR9XvFxkaHgn0Os6Ebx2UWDeYDxWr1RL8No25PZ0J-MeH'; //图片
        //接口
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$this->wechat->get_access_token()."&media_id={$media_id}";
        //echo $url;echo '</br>';
        //保存图片
        //实例化 guzzlehttp中的client类
        $client = new Client();
//        发送get请求
        $response = $client->get($url);
//        $h = $response->getHeaders();
//        echo '<pre>';print_r($h);echo '</pre>';die;
        //获取返回的头部信息 getHeader();
        //获取文件名 Content-disposition对应的是文件名
        $file_info = $response->getHeader('Content-disposition');
        // 处理文件名
//        var_dump($file_info);die;
        //rtrim()是截取右半部分的$file_info的名字，substr从-20开始
        //charlist字符列表
        $file_name = substr(rtrim($file_info[0],'"'),-20);
//        var_dump($file_name);die;
        //$wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $path = 'wechat/image/'.$file_name;
        //使用getBody方法可以获取响应的主体部分(body)，主体可以当成一个字符串或流对象使用


        //这是个流资源
//        dump($response->getBody());die;
        //disk(是filesystem.php的驱动);
        $re = Storage::disk('local')->put($path, $response->getBody());
        echo env('APP_URL').'storage/'.$path;//通过这个可以访问到图片
        dd($re);
        //return $file_name;
    }

    //获取临时视频素材
    public function get_video_source(){
        $media_id = 'Ou922egBK0qCUL3HFq_kklPRvueZc2EvS0jqx9X_rFFp49BZrg_kM4wug3x-DDsS'; //视频
        $url = 'http://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        $client = new Client();
//        发送get请求
        $response = $client->get($url);
//        dump($response);//返回一个响应里面包括 Stream流资源
//        dd();
//        dump($response->getBody());//这是一个Stream流资源
//        dd();
        $video_url = json_decode($response->getBody(),1)['video_url'];
//        dump($video_url);//访问此$video_url可以下载到本地
//       parse_url 本函数解析一个 URL 并返回一个关联数组，包含在 URL 中出现的各种组成部分。
//       parse_url 本函数不是用来验证给定 URL 的合法性的，只是将其分解为下面列出的部分。不完整的 URL 也被接受，parse_url()会尝试尽量正确地将其解析。
//        dump(parse_url($video_url)['path']);//"/vweixinp.tc.qq.com/1007_f4e044a0d3c44a9e80944b7deb9e9632.f10.mp4"
//        dd();
//        dump(parse_url($video_url));dd();//返回的是
        /**
         * array:4 [▼
        "scheme" => "http"
        "host" => "203.205.138.13"
        "path" => "/vweixinp.tc.qq.com/1007_f4e044a0d3c44a9e80944b7deb9e9632.f10.mp4"
        "query" => "vkey=B04B5882178E388B4CA17D1281F2A484094DF8AB79BAE1AC16A8E588DCCF0E99FE6F7A501EEB981BEBA2E4044A5CDBCC06C9EC52A6622874AFF09502F916D054A799A7A1EF3FF0C5E463BDD4831C7FEBBDF1B749A12B2F76&sha=0&save=1 ◀"
        ]
        */
        //文件名
        $file_name = explode('/',parse_url($video_url)['path'])[2];
//        dump($file_name);dd();//返回的是文件名
        //以下的$opts和$context是进化版file_get_contents method可以是post但是无法带参数
        //设置超时参数
        $opts=array(
            "http"=>array(
                "method"=>"GET",
                "timeout"=>3  //单位秒
            ),
        );
//        dump($opts);//原数据
//        dd();
        //创建数据流上下文
        $context = stream_context_create($opts);
//        dump($context);//好像是个文本流资源
        /**
         * stream-context resource @346 ▼
            options: array:1 [▼
            "http" => array:2 [▼
            "method" => "GET"
            "timeout" => 3
                    ]
                ]
            }
        */
//        dd();
        //$url请求的地址，例如：
        $read = file_get_contents($video_url,false, $context);
//        print_r($read);dd();//一堆乱码
        //存储 将一个字符串写入文件 参数一文件名，参数二数据 数组字符串和streanm资源都可以
        $re = file_put_contents('./storage/wechat/video/'.$file_name,$read);

        var_dump($re);
        die();
    }

    //获取临时音频素材
    public function get_voice_source()
    {
        $media_id = 'B_CaA9d3U4MCCMAgiMx0GZ0tDF_-ngwnnDhuxqbiPUfpX2Jw19U-BIpjHA9AYpZN';
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->wechat->get_access_token().'&media_id='.$media_id;
        //echo $url;echo '</br>';
        //保存图片
        $client = new Client();
        $response = $client->get($url);
//        $h = $response->getHeaders();
//        echo '<pre>';print_r($h);echo '</pre>';die;
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);
        //$wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $path = 'wechat/voice/'.$file_name;
        $re = Storage::put($path, $response->getBody());
        echo env('APP_URL').'/storage/'.$path;
        dd($re);
    }

    //自己布置的作业
//        获取永久的素材（三种）

    public function get_yongjiu_sucai()
    {
        $url="https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$this->wechat->get_access_token();
        $data=[
            "media_id"=>'KumWbj3jWGdSLPnLkQROIHoba3g3JdNHLuuR2c2MFL4'//视频素材
//            "media_id"=>''//语音素材
        ];
        $re=$this->wechat->post($url,json_encode($data));
//        dd($re);
        $re=json_decode($re,1);
        dd($re);
    }


}
