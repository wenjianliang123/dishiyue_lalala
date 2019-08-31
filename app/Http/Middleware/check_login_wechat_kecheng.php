<?php
namespace App\Http\Middleware;
use Closure;
class check_login_wechat_kecheng
{
    public function handle($request, Closure $next)
    {
        $loginInfo=session('kecheng_user_name');
        if(!empty($loginInfo)){
            return $next($request);
        }else{
            return redirect('/kecheng/login');
        }
    }
}
