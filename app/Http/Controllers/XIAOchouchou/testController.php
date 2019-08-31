<?php

namespace App\Http\Controllers\XIAOchouchou;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class testController extends Controller
{
    public function get_access_token()
    {
        $access_token_key = 'wechat_access_key';
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        if($redis->exists($access_token_key))
        {
            $access_token = $redis->get($access_token_key);
        }else{
            $access_token_info = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET')."");
            $access_token_info=json_decode($access_token_info,1);
            $access_token=$access_token_info['access_token'];
            $expires_in = $access_token_info['expires_in'];
            $redis->set($access_token_key,$access_token,$expires_in);
        }
        return $access_token;

    }

    public function add()
    {
        echo 111;
        return view('admin/fenxiao/user_list');
    }
}
