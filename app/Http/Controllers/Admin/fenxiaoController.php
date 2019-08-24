<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Tool\wechat;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use DB;
class fenxiaoController extends Controller
{
    public $wechat;
    public function __construct(wechat $wechat)
    {
        $this->wechat=$wechat;
    }
    //此控制器是分销 就是拉取新人 代理商
    /**
     * 用户列表
     */
    public function user_list()
    {
        $user_info = DB::connection('mysql_shop')->table('user_wechat')->get();
//        dd($user_info);
        $jsconfig = [
            'appid'=>env('WECHAT_APPID'),//APPID
            'timestamp'=>time(),
            'noncestr'=>time().rand(111111,999999).'wenjianliang',
        ];
        $sign = $this->compute_sign($jsconfig);
        $jsconfig['sign'] = $sign;
        $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//        dd($js_sdk_sign);
        return view('admin/fenxiao/user_list',['data'=>$user_info,'jsconfig'=>$jsconfig,'url'=>$url]);
    }
    //这是在做分享接口的那些
    /**
     *JSSDK签名计算
     * 签名算法
     * 签名生成规则如下：
     * 参与签名的字段包括noncestr（随机字符串）,
     * 有效的jsapi_ticket, timestamp（时间戳）,
     * url（当前网页的URL，不包含#及其后面部分） 。
     * 对所有待签名参数按照字段名的ASCII 码从小到大排序（字典序）后，
     * 使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串string1。
     * 这里需要注意的是所有参数名均为小写字符。
     * 对string1作sha1加密，字段名和字段值都采用原始值，不进行URL 转义。
     * 即signature=sha1(string1)。 示例：
     */
    public function compute_sign($param)
    {
        $current_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];     //当前调用 jsapi的 url
        $ticket = $this->wechat->get_jsapi_ticket();
        $str='jsapi_ticket='.$ticket.'&noncestr='.$param['noncestr'].'&timestamp='.$param['timestamp'].'&url='.$current_url;
        $signature=sha1($str);
        return $signature;
    }

    /**
     * 生成专属二维码
     */
    public function create_qrcode(Request $request)
    {
        $user_id = $request->all()['id']; //用户uid
        $user_id=intval($user_id);
//        dd($user_id);
        //用户uid就是专属推广码
        //生成带参数的二维码
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->wechat->get_access_token();
        $data=[
            "action_name"=>"QR_LIMIT_SCENE",
            "action_info"=> [
                "scene"=>[
                    "scene_id"=>$user_id,
                ],
            ]];
//        dump($user_id);die();
        //不对中文进行编码
        $re=$this->wechat->post($url,json_encode($data,JSON_UNESCAPED_UNICODE));
//        dd($re);
        $re=json_decode($re,1);
//        dump($re);die();
        //$re
        $ticket=$re['ticket'];
        $ticket=urlencode($ticket);
//        $url=$re['url'];//他其实就是二维码 不是浏览器的链接 可以通过前端 qr_code拓展使用url生成二维码
//        dd($ticket);
        //通过ticket换取二维码 file_get_contents（）适用于资源 获取视频的那种
        $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
//        $qr_code_info=json_decode($qr_code_info,1);
//        dump($url);
        /**
         * 保存图片 二维码存入larvel
         */
        //实例化 guzzlehttp中的client类
        $client = new Client();
//        发送get请求
        $response = $client->get($url);
//        $h = $response->getHeaders();
//        echo '<pre>';print_r($h);echo '</pre>';die;
        //获取返回的头部信息 getHeader();
        //获取文件信息
        $file_info = $response->getHeaders();
//        dump($file_info);die();
        //获取后缀名
        $ext=explode('/',$file_info['Content-Type'][0])[1];
//        dd($file_info);
//        dd($ext);
        // 处理文件名
//        var_dump($file_info);die;
        //rtrim()是截取右半部分的$file_info的名字，substr从-20开始
        //charlist字符列表
        $file_name =rand(1000,9999).'.'.$ext;
//        dd($file_name);
//        dd($result);//true
        //return $file_name;
        //$wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $path = 'qr_code/'.$file_name;
        //使用getBody方法可以获取响应的主体部分(body)，主体可以当成一个字符串或流对象使用
        //这是个流资源
//        dump($response->getBody());die;
        //disk(是filesystem.php的驱动);
//        dd($response->getBody());
        $result = Storage::disk('local')->put($path, $response->getBody());
        //返回二维码链接 通过这个可以访问到图片
//        echo env('APP_URL').'storage/'.$path;
        $qr_code_url=env('APP_URL').'storage/'.$path;
//
//        $user_id=implode('',$user_id);
//        dd($qr_code_url);
        //将专属的推广码和二维码地址写入数据库
        $user_info=DB::connection('mysql_shop')->table('user_wechat')->where('id',$user_id)->get();
//        dd($user_info);
        if(!empty($user_info))
        {
            $sss=DB::connection('mysql_shop')->table('user_wechat')->where('id',$user_id)->update([
                'agent_code'=>$user_id,
                'qrcode_url'=>$qr_code_url
            ]);
            if($sss){
                echo "专属二维码创建成功";
            }
        }else{
            echo '专属二维码创建失败';
        }
    }
    /**
     * 用户推广用户列表
     * @param Request $request
     */
    public function agent_list(Request $request)
    {
        $user_id = $request->all()['id']; //用户uid
//        dd($user_id);
        //user_agent 表数据 根据uid查询
        $data=DB::Connection('mysql_shop')->table('user_agent')->where('user_id',$user_id)->get();
        $data=json_decode(json_encode($data),1);
//        dd($data);
        return view('admin/fenxiao/agent_list',['data'=>$data]);
    }
}