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
    public $redis;

    public function __construct(wechat $wechat, wechat_biaoqian_controller $wechat_biaoqian_controller)
    {
        $this->wechat = $wechat;
        $this->wechat_biaoqian_controller = $wechat_biaoqian_controller;
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', '6379');
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
        $re = file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=" . $this->wechat->get_access_token() . "");
        $re = json_decode($re, 1);
//        dd($re);
        return view("admin/wechat_biaoqian/biaoqian_list_1", ['data' => $re]);
    }

    //创建标签的视图
    public function create_biaoqian_view()
    {
        return view('admin/wechat_biaoqian/create_biaoqian_view');
    }

    //修改标签的视图  --可优化
    public function edit_biaoqian_view(Request $request)
    {
//        可行思路 拿到标签id 两次foreach 一个所有标签列表 一个只有标签id 但是需要转化
//            如果标签列表的id等于发过来的标签id 输出到视图name
        $id = $request->id;
//        dd($id);
        $tag_id = ["tag_id" => [$id]];
        //拿到所有标签
        $re = file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=" . $this->wechat->get_access_token() . "");
        $re = json_decode($re, 1);
        $re_arr = $re['tags'];
//        dd($re_arr);
        foreach ($re_arr as $v) {
            foreach ($tag_id['tag_id'] as $vo) {
                if ($vo == $v['id']) {
                    return view('admin/wechat_biaoqian/eidt_biaoqian_view', ['id' => $vo, 'name' => $v['name']]);
                }
            }
        }
//
    }

    //获取标签下用户列表
    public function get_tag_user_view(Request $request)
    {
        //获取id根据id
        $id = $request->id;
        //问题：怎么传值 解决 调用方法($id)
//        传给获取标签下订蛋用户列表的方法
        $data = $this->wechat_biaoqian_controller->get_tag_user($id);
//        dd($data);
//        dd($id);
        $tag_id = ["tag_id" => [$id]];
        //拿到所有标签
        $re = file_get_contents("https://api.weixin.qq.com/cgi-bin/tags/get?access_token=" . $this->wechat->get_access_token() . "");
        $re = json_decode($re, 1);
        $re_arr = $re['tags'];
//        dd($re_arr);
        foreach ($re_arr as $v) {
            foreach ($tag_id['tag_id'] as $vo) {
                if ($vo == $v['id']) {
                    return view('admin/wechat_biaoqian/tag_user_list', ['data' => $data, 'id' => $vo, 'name' => $v['name']]);
                }
            }
        }
    }

    //根据标签群发消息
    public function Batch_send_tag_user_info_view(Request $request)
    {
        $tag_id = implode($request->all());
//        dd($tag_id);
        return view('admin/wechat_biaoqian/Batch_send_tag_user_info_view', ['tag_id' => $tag_id]);
    }
    //接口配置的url
    //接收普通消息
    /*
         *  ToUserName	开发者（测试号的微信号）微信号
            FromUserName	被发送方帐号（一个OpenID）
            CreateTime	消息创建时间 （整型）
            MsgType	消息类型，文本为text
            Content	文本消息内容
            MsgId	消息id，64位整型
        */
    public function jiekou_peizhi_url() //固定youjia好像写一遍就可以一直使用 不用在postman中重复给值
    {
        // （只能用于线上）第一次无法配置成功使用以下两行代码
//        echo $_GET['echostr'];
//        die();
//        echo "您已经进入接口配置的url";
        //        echo 1;dd();
        //$this->checkSignature();
        //php用file_get_contents("php://input")可以接收xml数据
        $data = file_get_contents("php://input");
        //解析XML LIBXML_NOCDATA - 将 CDATA 设置为文本节点 传过来的数据中带有CDATA样式的前缀 将其设为文本节点
        //将一个XML字符串装载入一个对象中
//        dd($data);
        $xml = simplexml_load_string($data,'SimpleXMLElement', LIBXML_NOCDATA);        //将 xml字符串 转换成对象
        $xml = (array)$xml; //转化成数组
        //写入日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n".'<<<<<<<';
//      //file_put_contents — 将一个字符串写入文件 和依次调用 fopen()，fwrite() 以及 fclose() 功能一样。
//        FILE_APPEND	如果文件 filename 已经存在，追加数据而不是覆盖。
        file_put_contents(storage_path('logs/Receive_normal_messages.log'),$log_str,FILE_APPEND);
//        print_r($xml['MsgType']);die();
//        \Log::Info(json_encode($xml));
        if($xml['MsgType']=='text'){
//            $message = '您好!';
//            $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
//            echo $xml_str;
<<<<<<< HEAD



=======
>>>>>>> 771ce77e5b52e8aba9320d3502e086049c634cfc
            $preg_result = preg_match('/.*?油价/',$xml['Content']);
//            dd($preg_result);
            if($preg_result){
                //查询油价
                $city = substr($xml['Content'],0,-6);
                $price_info = file_get_contents('http://www.wenjianliang.top/youjia/api');
                $price_arr = json_decode($price_info,1);
//                dd($price_arr);
                $support_arr = [];
                foreach($price_arr['result'] as $v){
                    $support_arr[] = $v['city'];
                }
                if(!in_array($city,$support_arr)){
                    $message = '查询城市不支持！';
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                    echo $xml_str;
                    die();
                }
                foreach($price_arr['result'] as $v){
                    if($city == $v['city']){
                        $this->redis->incr($city);
                        $find_num = $this->redis->get($city);
                        //缓存操作
                        if($find_num >1){
                            if($this->redis->exists($city.'youjia')){
                                //存在
                                $v_info = $this->redis->get($city.'youjia');
                                $v = json_decode($v_info,1);
                            }else{
                                $this->redis->set($city.'youjia',json_encode($v),300);
                            }
                        }
                        //$message = $city.'目前油价：'."\n";
                        $message = $city.'目前油价：'."\n".'92h：'.$v['92h']."\n".'95h：'.$v['95h']."\n".'98h：'.$v['98h']."\n".'0h：'.$v['0h'];
                        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                        echo $xml_str;
                        die();
                    }
                }
            }
            /*$message = '你好!';
            $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
            echo $xml_str;*/
        }elseif($xml['MsgType']=='event'){
            if($xml['Event']=='subscribe'){
//                echo 222;die;

                /*if(isset($xml['EventKey'])){
                    //拉取新人的操作
//                    print_r($xml['EventKey']);die();
                    $agent_code=$xml['EventKey'];
//                    echo $agent_code;die();//10
                    $agent_code = explode('_',$agent_code)[1];
//                    echo $agent_code;die();
                    $agent_info = DB::connection('mysql_shop')->table('user_agent')->where(['user_id'=>$agent_code,'openid'=>$xml['FromUserName']])->first();
                    if(empty($agent_info)){
                        DB::connection('mysql_shop')->table('user_agent')->insert([
                            'user_id'=>$agent_code,
                            'openid'=>$xml['FromUserName'],
                            'add_time'=>time()
                        ]);
//                        echo 'okok';die();
                    }else{
//                        echo 11;
                    }
                }*/
                $open_id=$xml['FromUserName'];
                $user_info=$this->wechat->get_user_info($open_id);
//                dd($user_info);
                $user_name=$user_info['nickname'];
                $us=DB::connection('mysql_shop')->table('user_wechat')->insert([
                    'name'=>$user_name,
                    'state'=>1,
                    'register_time'=>time(),
                    'password'=>''
                ]);
//                dd($open_id);
//                $open_id=implode('',$open_id);
                $huanying="欢迎";
                $jiewei="进入选课系统";
                $message = $huanying.$user_name.$jiewei;
                $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
                echo $xml_str;
                //表白 -无故会出现故障 或者无反应
            }elseif($xml['Event'] == 'CLICK'){
                if($xml['EventKey'] == 'my_biaobai'){
                    $open_id=$xml['FromUserName'];
                    //此处的打印结果是openid 拿到openid 去查youjia 取出名字 放入条件
//                    dd($open_id);
                    $nickname_info=$this->wechat->get_user_info($open_id);
//                    dd($nickname_info);
                    $nickname_1=$nickname_info['nickname'];
//                    dd($nickname_1);
                    $biaobai_info = DB::connection('mysql_shop')->table('wechat_biaobai')->where(['user_name'=>$nickname_1])->get()->toArray();
//                    dd($biaobai_info);
                    $num=count($biaobai_info);
//                    dd($num);
                    $message = '';
                    foreach($biaobai_info as $k=>$v){
                        $message .= intval($k+1).'、'."《《收到》》".$v->push_user.'表白内容：'.$v->biaobai_content."\n";
                    }
                    $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['."共收到".$num.'条'."\n".$message.']]></Content></xml>';
//                    $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
//                    dump($xml_str);
                    echo $xml_str;
                }elseif ($xml['EventKey'] == 'my_kecheng'){
                    $user_name=session('kecheng_user_name');
                    $panduan_kecheng=DB::connection('mysql_shop')->table('bayue_yuekao_kecheng')->where('user_name',$user_name)->get()->toarray();

                        //已经有课程
                        $open_id=$xml['FromUserName'];
                        //此处的打印结果是openid 拿到openid 去查youjia 取出名字 放入条件
//                    dd($open_id);
                        $nickname_info=$this->wechat->get_user_info($open_id);
//                    dd($nickname_info);
                        $nickname_1=$nickname_info['nickname'];
//                    dd($nickname_1);
                        $kecheng_info = DB::connection('mysql_shop')->table('bayue_yuekao_kecheng')->where(['user_name'=>$nickname_1])->orderBy('kecheng_id','desc')->first();
//                    dd($biaobai_info);
                        $kecheng_info=json_decode(json_encode($kecheng_info),1);
                        $tishi="你好,".$this->wechat->get_user_info($xml['FromUserName'])['nickname']."同学，你当前的课程安排如下";
//                    dd($kecheng_info);
//                    $message = '';

                        $message ="第一节".$kecheng_info['kecheng_1']."\n"."第二节".$kecheng_info['kecheng_2']."\n"."第三节".$kecheng_info['kecheng_3']."\n"."第四节".$kecheng_info['kecheng_4']."\n";

                        $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$tishi."\n".$message.']]></Content></xml>';
//                    $xml_str = '<xml><ToUserName><![CDATA['.$xml['FromUserName'].']]></ToUserName><FromUserName><![CDATA['.$xml['ToUserName'].']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['.$message.']]></Content></xml>';
//                    dump($xml_str);
                        echo $xml_str;


                }
                //老师的地理位置 看不懂
            }elseif($xml['Event'] == 'location_select') {
                $message = $xml['SendLocationInfo']->Label;
                \Log::Info($message);
                $xml_str = '<xml><ToUserName><![CDATA[otAUQ1UtX-nKATwQMq5euKLME2fg]]></ToUserName><FromUserName><![CDATA[' . $xml['ToUserName'] . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $message . ']]></Content></xml>';
                echo $xml_str;
            }
        }
    }
}