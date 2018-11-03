<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:25
 */
namespace App\Modules\Callback\Middleware ;


use App\Common\Contracts\Middleware;
use Closure;

class OneMiddle extends Middleware {

    public function handle($request, Closure $next)
    {
         $request['num'] = $request['num'] + 1;
         $request['one'] = $request['num'];
        return $next($request);

    }

}