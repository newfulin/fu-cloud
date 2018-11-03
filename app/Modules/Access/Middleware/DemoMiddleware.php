<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 10:57
 */

namespace App\Modules\Access\Middleware;


use Illuminate\Http\Request;

class DemoMiddleware
{


    public function handle(Request $request, \Closure $next)
    {
        $request['detail'] = 'detail';
        return $next($request);
    }


}