<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/25
 * Time: 08:51
 */
namespace App\Modules\Transaction\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Transaction\Transfer;
use Closure;

class RouteMiddleware extends Middleware {


    public function handle($request, Closure $next)
    {
        //这里需要调用路由
        $request['route'] = Transfer::service('TransactionRoute')
                            ->pass($request)
                            ->run();

        return $next($request);


        $resp = $next($request);


        return $resp;

    }


}