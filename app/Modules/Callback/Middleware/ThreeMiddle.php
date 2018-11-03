<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/1
 * Time: 16:32
 */
namespace App\Modules\Callback\Middleware ;
use App\Common\Contracts\Middleware;
use Closure;

class ThreeMiddle extends Middleware {

    public function handle($request, Closure $next)
    {
        $request['num'] = $request['num'] + 1;
        $request['three'] = $request['num'];
        return $next($request);
    }


}