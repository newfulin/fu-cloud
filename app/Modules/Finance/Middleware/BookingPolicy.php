<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/27
 * Time: 10:35
 */

namespace App\Modules\Finance\Middleware;

use App\Common\Contracts\Middleware;
use Closure;

/**
 * 策略类<中间件>
 * @desc 暂不开通策略
 */
class BookingPolicy extends Middleware{

    public function handle($request, Closure $next)
    {
        $request['policy']=$request['code'];
        return $next($request);
    }


}