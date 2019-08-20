<?php

namespace App\Http\Middleware;

use Closure;

class checkLogin_xinwen
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
        $loginInfo=session('user_info');
        if(!empty($loginInfo)){
            return $next($request);
        }else{
            return redirect('yuekao/login');
        }
    }
}
