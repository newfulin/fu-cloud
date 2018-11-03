<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/1/24
 * Time: 17:08
 */
namespace App\Modules\Transaction\Middleware\Business ;

use App\Common\Contracts\Middleware;
use App\Modules\Transaction\Request;
use Closure;

class A0710 extends Middleware {


    public function handle($request, Closure $next)
    {
        $request['A0710'] = '031';
        
        return $next($request);
    }


}