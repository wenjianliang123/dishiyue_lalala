<?php

namespace App\Http\Middleware;

use Closure;

class check_login_wechat_liuyan
{
    public function handle($request, Closure $next)
    {
        $loginInfo=session('wechat_user_name');
        if(!empty($loginInfo)){
            return $next($request);
        }else{
            return redirect('zhoukao/liuyan/login');
        }
    }
}
