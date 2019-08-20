<?php

namespace App\Http\Middleware;

use Closure;

class chekuLogin
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
        $loginInfo=session('loginInfo');
        if(!empty($loginInfo)){
            return $next($request);
        }else{
            return redirect('login');
        }
    }
}
