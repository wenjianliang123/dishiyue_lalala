<?php

namespace App\Http\Controllers\Jiekou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Tool\wechat;

class youjia_controller extends Controller
{
    public $wechat;
    public $redis;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1','6379');
    }
    public function youjia_api()
    {
        $info = '{"resultcode":"200","reason":"查询成功!","result":[{"city":"北京","b90":"-","b93":"6.66","b97":"6.66","b0":"6.66","92h":"6.66","95h":"6.97","98h":"7.95","0h":"6.21"},{"city":"上海","b90":"-","b93":"6.51","b97":"6.93","b0":"6.15","92h":"6.51","95h":"6.93","98h":"7.63","0h":"6.15"},{"city":"江苏","b90":"-","b93":"6.52","b97":"6.94","b0":"6.14","92h":"6.52","95h":"6.94","98h":"7.82","0h":"6.14"},{"city":"天津","b90":"-","b93":"6.68","b97":"6.99","b0":"6.17","92h":"6.53","95h":"6.90","98h":"7.82","0h":"6.17"},{"city":"重庆","b90":"-","b93":"6.66","b97":"6.99","b0":"6.25","92h":"6.62","95h":"6.99","98h":"7.88","0h":"6.25"},{"city":"江西","b90":"-","b93":"6.51","b97":"6.99","b0":"6.21","92h":"6.51","95h":"6.99","98h":"7.99","0h":"6.21"},{"city":"辽宁","b90":"-","b93":"6.52","b97":"6.95","b0":"6.09","92h":"6.52","95h":"6.95","98h":"7.57","0h":"6.09"},{"city":"安徽","b90":"-","b93":"6.51","b97":"6.99","b0":"6.20","92h":"6.51","95h":"6.99","98h":"7.82","0h":"6.20"},{"city":"内蒙古","b90":"-","b93":"6.49","b97":"6.92","b0":"6.06","92h":"6.49","95h":"6.92","98h":"7.60","0h":"6.06"},{"city":"福建","b90":"-","b93":"6.52","b97":"6.96","b0":"6.17","92h":"6.52","95h":"6.96","98h":"7.62","0h":"6.17"},{"city":"宁夏","b90":"-","b93":"6.46","b97":"6.82","b0":"6.07","92h":"6.46","95h":"6.82","98h":"8.01","0h":"6.07"},{"city":"甘肃","b90":"-","b93":"6.44","b97":"6.88","b0":"6.08","92h":"6.44","95h":"6.88","98h":"7.32","0h":"6.08"},{"city":"青海","b90":"-","b93":"6.50","b97":"6.97","b0":"6.11","92h":"6.50","95h":"6.97","98h":"0","0h":"6.11"},{"city":"广东","b90":"-","b93":"6.57","b97":"7.11","b0":"6.18","92h":"6.57","95h":"7.11","98h":"7.99","0h":"6.18"},{"city":"山东","b90":"-","b93":"6.52","b97":"7.00","b0":"6.16","92h":"6.52","95h":"7.00","98h":"7.72","0h":"6.16"},{"city":"广西","b90":"-","b93":"6.61","b97":"7.14","b0":"6.23","92h":"6.61","95h":"7.14","98h":"7.92","0h":"6.23"},{"city":"山西","b90":"-","b93":"6.50","b97":"7.02","b0":"6.23","92h":"6.50","95h":"7.02","98h":"7.72","0h":"6.23"},{"city":"贵州","b90":"-","b93":"6.67","b97":"7.05","b0":"6.28","92h":"6.67","95h":"7.05","98h":"7.95","0h":"6.28"},{"city":"陕西","b90":"-","b93":"6.44","b97":"6.80","b0":"6.08","92h":"6.44","95h":"6.80","98h":"7.60","0h":"6.08"},{"city":"海南","b90":"-","b93":"7.66","b97":"8.13","b0":"6.26","92h":"7.66","95h":"8.13","98h":"9.18","0h":"6.26"},{"city":"四川","b90":"-","b93":"6.58","b97":"7.09","b0":"6.27","92h":"6.58","95h":"7.09","98h":"7.72","0h":"6.27"},{"city":"河北","b90":"-","b93":"6.66","b97":"6.90","b0":"6.17","92h":"6.53","95h":"6.90","98h":"7.73","0h":"6.17"},{"city":"西藏","b90":"-","b93":"7.43","b97":"7.86","b0":"6.73","92h":"7.43","95h":"7.86","98h":"0","0h":"6.73"},{"city":"河南","b90":"-","b93":"6.55","b97":"6.99","b0":"6.16","92h":"6.55","95h":"6.99","98h":"7.64","0h":"6.16"},{"city":"新疆","b90":"-","b93":"6.42","b97":"6.92","b0":"6.06","92h":"6.42","95h":"6.92","98h":"7.73","0h":"6.06"},{"city":"黑龙江","b90":"-","b93":"6.48","b97":"6.87","b0":"5.95","92h":"6.48","95h":"6.87","98h":"7.84","0h":"5.95"},{"city":"吉林","b90":"-","b93":"6.51","b97":"7.02","b0":"6.10","92h":"6.51","95h":"7.02","98h":"7.65","0h":"6.10"},{"city":"云南","b90":"-","b93":"6.69","b97":"7.18","b0":"6.25","92h":"6.69","95h":"7.18","98h":"7.86","0h":"6.25"},{"city":"湖北","b90":"-","b93":"6.55","b97":"7.01","b0":"6.16","92h":"6.55","95h":"7.01","98h":"7.58","0h":"6.16"},{"city":"浙江","b90":"-","b93":"6.52","b97":"6.94","b0":"6.16","92h":"6.52","95h":"6.94","98h":"7.60","0h":"6.16"},{"city":"湖南","b90":"-","b93":"6.50","b97":"6.90","b0":"6.23","92h":"6.50","95h":"6.90","98h":"7.71","0h":"6.23"}],"error_code":0}';
        echo $info;
    }

    public function youjia_tiaozheng_test()
    {
        /*
//        $this->redis = new \Redis();
//        $this->redis->connect('127.0.0.1','6379');
//        dd(1);
//            return ;
            //业务逻辑
            $price_info = file_get_contents('http://www.wenjianliang.top/youjia/api');
            $price_arr = json_decode($price_info,1);
//        dd($price_arr);
            foreach($price_arr['result'] as $v){
//                dd($v);
                if($this->redis->exists($v['city']."youjia")){
//                    dd($this->redis->get($v['city']."youjia"));
                    $redis_info = json_decode($this->redis->get($v['city']."youjia"),1);
//                    dump($redis_info);die();
                    foreach ($v as $k=>$vv){
//                        dump($k);die();

                        if($vv != $redis_info[$k]){
                            //推送模板消息
//                            dd(1);
                            $openid_info = $this->wechat->app->user->list($nextOpenId = null);
                            $openid_list = $openid_info['data'];
//                            dd($openid_list);
                            foreach ($openid_list['openid'] as $vo){
                                $this->wechat->app->template_message->send([
                                    'touser' =>'oMbARt6tCM2dJZL6MjdKPmOxrpMY',//$vo,
                                    'template_id' =>'x2mkWXTbj7bR0R3-tCXTdzPWLt9CxEoiaNYY8J06HSY',
                                    'url' =>'http://www.wenjianliang.top',
                                    'data' =>[
                                        'keyword1' => $v['city'],
                                        'keyword2' => '该地址的油价发生调整,请细心留意',
                                    ],
                                ]);
                            }
                            dd('发送完毕');
                        }else{
                            echo '未进行;更改———8888———'."<hr />";die();
                        }
                    }
                }else{
                   echo "redis中的城市+youjia不存在"."<hr />";die();
                }
            }
            // })->daily();


        /*$price_info = file_get_contents('http://www.wenjianliang.top/youjia/api');
        $price_arr = json_decode($price_info,1);
        foreach($price_arr['result'] as $v){
            if($this->redis->exists($v['city'].'youjia')){
                $redis_info = json_decode($this->redis->get($v['city'].'youjia'),1);
                foreach ($v as $k=>$vv){
                    if($vv != $redis_info[$k]){
                        //推送模板消息
                        $openid_info = $this->wechat->app->user->list($nextOpenId = null);
                        $openid_list = $openid_info['data'];
                        foreach ($openid_list['openid'] as $vo){
                            $this->wechat->app->template_message->send([
                                'touser' => $vo,
                                'template_id' => 'x2mkWXTbj7bR0R3-tCXTdzPWLt9CxEoiaNYY8J06HSY	',
                                'url' => 'http://www.wenjianliang.top',
                                'data' => [
                                    'keyword1' => $v['city'],
                                    'keyword2' => '该地址的油价发生调整，请细心留意',
                                ],
                            ]);
                        }
                    }
                }
            }
        }
        dd();
    */
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $app = app('wechat.official_account');
        \Log::Info('进入自动任务了');

        //业务逻辑
        $price_info = file_get_contents('http://www.wenjianliang.top/youjia/api');
        $price_arr = json_decode($price_info,1);
        foreach($price_arr['result'] as $v){
            if($redis->exists($v['city'].'youjia')){
                $redis_info = json_decode($redis->get($v['city'].'youjia'),1);
//                dd($redis_info);
                /*array:9 [
                  "city" => "北京"
                  "b90" => "-"
                  "b93" => "6.50"
                  "b97" => "6.97"
                  "b0" => "6.21"
                  "92h" => "6.54"
                  "95h" => "6.97"
                  "98h" => "7.95"
                  "0h" => "6.21"
                ]*/
//                dd($v);
                /*array:9 [
                          "city" => "北京"
                          "b90" => "-"
                          "b93" => "6.66"
                          "b97" => "6.66"
                          "b0" => "6.66"
                          "92h" => "6.54"
                          "95h" => "6.97"
                          "98h" => "7.95"
                          "0h" => "6.21"
                        ]*/
                foreach ($v as $k=>$vv){
//                    dd($k);//city
                    if($vv != $redis_info[$k]){
//                        dd($vv!=$redis_info[$k]);//true
                        //推送模板消息
                        $openid_info = $app->user->list($nextOpenId = null);
                        $openid_list = $openid_info['data'];
                        //要给所有人群发的话 遍历open_list 将$app这个模板发送方法包起来
                        $app->template_message->send([
                            'touser' => 'oMbARt6tCM2dJZL6MjdKPmOxrpMY',//$vo,
                            'template_id' => 'x2mkWXTbj7bR0R3-tCXTdzPWLt9CxEoiaNYY8J06HSY',
                            'url' => 'http://www.wenjianliang.top',
                            'data' => [
                                'keyword1' => $v['city'],
                                'keyword2' => '该地址的油价发生调整,请细心留意',
                            ],
                        ]);

                    }
                }
            }
        }
    }





}
