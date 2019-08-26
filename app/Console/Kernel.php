<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Tools\Wechat;

class Kernel extends ConsoleKernel
{
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
        // $schedule->command('inspire')
        //          ->hourly();

        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $schedule->call(function (Wechat $wechat,$redis) {
            return ;
            //业务逻辑
            $price_info = file_get_contents('http://shopdemo.18022480300.com/price/api');
            $price_arr = json_decode($price_info,1);
            foreach($price_arr['result'] as $v){
                if($redis->exists($v['city'].'信息')){
                    $redis_info = json_decode($this->redis->get($v['city'].'信息'),1);
                    foreach ($v as $k=>$vv){
                        if($vv != $redis_info[$k]){
                            //推送模板消息
                            $openid_info = $wechat->app->user->list($nextOpenId = null);
                            $openid_list = $openid_info['data'];
                            foreach ($openid_list['openid'] as $vo){
                                $wechat->app->template_message->send([
                                    'touser' => $vo,
                                    'template_id' => 'hy-ju5jnMvV0PWVvJ4LMlg1ky_WQ91DtOrNYRQpfoq0',
                                    'url' => 'http://shopdemo.18022480300.com',
                                    'data' => [
                                        'first' => $v['city'],
                                        'keyword1' => '该地址的油价发生调整,请细心留意',
                                    ],
                                ]);
                            }
                        }
                    }
                }
            }
            // })->daily();
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
