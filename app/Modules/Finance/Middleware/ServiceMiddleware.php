<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/26
 * Time: 16:32
 */

namespace App\Modules\Finance\Middleware;
use App\Common\Contracts\Middleware;
use Closure ;

class ServiceMiddleware extends Middleware {

    public function handle($request, Closure $next )
    {
        $request['dddddddd'] = '123123';
        return $next($request);
    }


}