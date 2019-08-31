<?php

namespace App\Http\Middleware;

use Closure;

class Login
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
//        echo "这是前置中间件";
//        echo "<br />";
//        return $next($request);
        /**
         * 方法一   判断 Session 中是否存在某个值
         */
        if ($request->session()->has('user_name')) {
//            echo "您的用户名为".session('user_name');
//            echo "<br />";
        }else{
            return redirect('admin/login/login');
        }
        /**
         * 方法二   判断 Session 中是否存在某个值
         */
//        if(!$request->session()->exists('user_name')){
//            return redirect('/login');
//        }

        $response = $next($request);

        // Perform action //这个不用管
//        echo "<br />";
//        echo "这是后置中间件";
        return $response;

    }


}
