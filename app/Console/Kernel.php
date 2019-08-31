<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Log;
class Kernel extends ConsoleKernel
{
    public $wechat;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//         $schedule->command('inspire')
//                  ->hourly();

        $schedule->call(function () {

            $redis = new \Redis();
            $redis->connect('127.0.0.1','6379');
            $app = app('wechat.official_account');
            \Log::Info('进入自动任务了');
            dd();


//          业务逻辑一 油价 可以用
           /* $redis = new \Redis();
            $redis->connect('127.0.0.1','6379');
            $app = app('wechat.official_account');
            \Log::Info('进入自动任务了');
            die();
            //业务逻辑
            //油价调整提醒
            /*$price_info = file_get_contents('http://www.wenjianliang.top/youjia/api');
            $price_arr = json_decode($price_info,1);
            foreach($price_arr['result'] as $v){
                if($redis->exists($v['city'].'youjia')){
                    $redis_info = json_decode($redis->get($v['city'].'youjia'),1);
                    foreach ($v as $k=>$vv){
                        if($vv != $redis_info[$k]){
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
            }*/

           /*
            //业务逻辑二 课程 可以用
            //课程提醒
            //推送模板消息
            $openid_info = $app->user->list($nextOpenId = null);
            $openid_list = $openid_info['data'];
//
            //要给所有人群发的话 遍历open_list 将$app这个模板发送方法包起来
            foreach ($openid_list['openid'] as $vqq){
//            $user_name=$this->wechat->get_user_info($vqq)['nickname'];
                $user_name= $app->user->get($vqq)['nickname'];
//        dd($user_name);
                $kecheng_info = DB::connection('mysql_shop')->table('bayue_yuekao_kecheng')->where(['user_name'=>$user_name])->orderBy('kecheng_id','desc')->first();
                $kecheng_info=json_decode(json_encode($kecheng_info),1);
//        dd($kecheng_info);
                $kecheng_info_1=$kecheng_info;
                $app->template_message->send([
                    'touser' => $vqq,
                    'template_id' =>'gpUZmcHS-r8sXbD-qZaXkrR2zPrD14jZV-ceQWpSSWA',
                    'url' => 'http://www.wenjianliang.top',
                    'data' => [
                        'first' => $app->user->get($vqq)['nickname'],
                        'keyword1' => $kecheng_info_1['kecheng_1'],
                        'keyword2' => $kecheng_info_1['kecheng_2'],
                        'keyword3' => $kecheng_info_1['kecheng_3'],
                        'keyword4' => $kecheng_info_1['kecheng_4'],
                    ],
                ]);
            }

//            dailyAt('20:00')
//            everyMinute()
                */
    })->everyMinute();



    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
