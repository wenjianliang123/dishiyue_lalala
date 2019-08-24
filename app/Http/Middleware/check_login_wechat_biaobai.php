<?php

namespace App\Http\Middleware;

use Closure;

class check_login_wechat_biaobai
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $loginInfo=session('wechat_user_name');
        if(!empty($loginInfo)){
            return $next($request);
        }else{
            return redirect('biaobai/login');
        }
    }
}
