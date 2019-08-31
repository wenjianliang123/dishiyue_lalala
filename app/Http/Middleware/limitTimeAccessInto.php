<?php

namespace App\Http\Middleware;

use Closure;

class limitTimeAccessInto
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
        /**
         * 方法一
         */

/*        $time=date('H');
        if ($time<9||$time>17)
        {
            echo "<script>alert('当前非可访问时间，请在9：00-17：00时间内访问'),window.history.go(-1)</script>";die();
        }*/


        /**
         * 方法二  老师推荐
         */
        $start_time=strtotime('09:00:00');
        $end_time=strtotime('17:00:00');
        $now=time();
        if($now<$start_time||$now>$end_time)
        {
            echo "<script>alert('当前非可访问时间，请在9：00-17：00时间内访问'),window.history.go(-1)</script>";die();
        }
        return $next($request);
    }
}
