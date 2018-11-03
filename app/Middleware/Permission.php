<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2018/2/26
 * Time: 13:58
 */
namespace App\Middleware ;

use Closure;
use Illuminate\Http\Request;

class Permission {

    public function handle(Request $request , Closure $next)
    {

        $claims = $request->user()->claims;
        //这里可以判断 $claims->role  : P1101.....
        Err("权限不足");

        return $next($request);
    }
}