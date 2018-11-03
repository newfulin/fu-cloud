<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/24
 * Time: 17:09
 */
namespace App\Modules\Transaction\Middleware;

use App\Common\Contracts\Middleware;
use App\Modules\Transaction\Request;
use Closure ;

class CashierMiddleware extends Middleware {

    public function handle($request , Closure $next)
    {
        $request['cash'] = '123';
        return $next($request);
    }
}